<?php


namespace WebConsole\Extension\Support;


use WebConsole\Extension\Support\BaseJsonRpcServer;
use Exception;

// RPC Server
class WebConsoleRPCServer extends BaseJsonRpcServer {
    protected $home_directory = '';

    private function error($message) {
        throw new Exception($message);
    }

    // Authentication
    private function authenticate_user($user, $password) {
        $user = trim((string) $user);
        $password = trim((string) $password);

        if ($user && $password) {
            $account = config('webconsole.account');
            $password_hash_algorithm = config('webconsole.password_hash_algorithm');
            if (isset($account[$user]) && !wc_is_empty_string($account[$user])) {
                if ($password_hash_algorithm) $password = wc_get_hash($password_hash_algorithm, $password);

                if (wc_is_equal_strings($password, $account[$user]))
                    return $user . ':' . get_hash('sha256', $password);
            }
        }
        throw new Exception("Incorrect user or password");
    }

    private function authenticate_token($token) {
        global $NO_LOGIN;
        if ($NO_LOGIN) return true;

        $token = trim((string) $token);
        $token_parts = explode(':', $token, 2);

        if (count($token_parts) == 2) {
            $user = trim((string) $token_parts[0]);
            $password_hash = trim((string) $token_parts[1]);

            if ($user && $password_hash) {
                global $ACCOUNTS;

                if (isset($ACCOUNTS[$user]) && !wc_is_empty_string($ACCOUNTS[$user])) {
                    $real_password_hash = wc_get_hash('sha256', $ACCOUNTS[$user]);
                    if (wc_is_equal_strings($password_hash, $real_password_hash)) return $user;
                }
            }
        }

        throw new Exception("Incorrect user or password");
    }

    private function get_home_directory($user) {
        global $HOME_DIRECTORY;

        if (is_string($HOME_DIRECTORY)) {
            if (!wc_is_empty_string($HOME_DIRECTORY)) return $HOME_DIRECTORY;
        }
        else if (is_string($user) && !wc_is_empty_string($user) && isset($HOME_DIRECTORY[$user]) && !wc_is_empty_string($HOME_DIRECTORY[$user]))
            return $HOME_DIRECTORY[$user];

        return getcwd();
    }

    // Environment
    private function get_environment() {
        $hostname = function_exists('gethostname') ? gethostname() : null;
        return array('path' => getcwd(), 'hostname' => $hostname);
    }

    private function set_environment($environment) {
        $environment = !empty($environment) ? (array) $environment : array();
        $path = (isset($environment['path']) && !wc_is_empty_string($environment['path'])) ? $environment['path'] : $this->home_directory;

        if (!wc_is_empty_string($path)) {
            if (is_dir($path)) {
                if (!@chdir($path)) return array('output' => "Unable to change directory to current working directory, updating current directory",
                    'environment' => $this->get_environment());
            }
            else return array('output' => "Current working directory not found, updating current directory",
                'environment' => $this->get_environment());
        }
    }

    // Initialization
    private function initialize($token, $environment) {
        $user = $this->authenticate_token($token);
        $this->home_directory = $this->get_home_directory($user);
        $result = $this->set_environment($environment);

        if ($result) return $result;
    }

    // Methods
    public function login($user, $password) {
        $result = array('token' => $this->authenticate_user($user, $password),
            'environment' => $this->get_environment());
        $home_directory = $this->get_home_directory($user);
        if (!wc_is_empty_string($home_directory)) {
            if (is_dir($home_directory)) $result['environment']['path'] = $home_directory;
            else $result['output'] = "Home directory not found: ". $home_directory;
        }

        return $result;
    }

    public function cd($token, $environment, $path) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $path = trim((string) $path);
        if (wc_is_empty_string($path)) $path = $this->home_directory;

        if (!wc_is_empty_string($path)) {
            if (is_dir($path)) {
                if (!@chdir($path)) return array('output' => "cd: ". $path . ": Unable to change directory");
            }
            else return array('output' => "cd: ". $path . ": No such directory");
        }

        return array('environment' => $this->get_environment());
    }

    public function completion($token, $environment, $pattern, $command) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $scan_path = '';
        $completion_prefix = '';
        $completion = array();

        if (!empty($pattern)) {
            if (!is_dir($pattern)) {
                $pattern = dirname($pattern);
                if ($pattern == '.') $pattern = '';
            }

            if (!empty($pattern)) {
                if (is_dir($pattern)) {
                    $scan_path = $completion_prefix = $pattern;
                    if (substr($completion_prefix, -1) != '/') $completion_prefix .= '/';
                }
            }
            else $scan_path = getcwd();
        }
        else $scan_path = getcwd();

        if (!empty($scan_path)) {
            // Loading directory listing
            $completion = array_values(array_diff(scandir($scan_path), array('..', '.')));
            natsort($completion);

            // Prefix
            if (!empty($completion_prefix) && !empty($completion)) {
                foreach ($completion as &$value) $value = $completion_prefix . $value;
            }

            // Pattern
            if (!empty($pattern) && !empty($completion)) {
                // For PHP version that does not support anonymous functions (available since PHP 5.3.0)
                function filter_pattern($value) {
                    global $pattern;
                    return !strncmp($pattern, $value, strlen($pattern));
                }

                $completion = array_values(array_filter($completion, 'filter_pattern'));
            }
        }

        return array('completion' => $completion);
    }

    public function run($token, $environment, $command) {
        $result = $this->initialize($token, $environment);
        if ($result) return $result;

        $output = ($command && !wc_is_empty_string($command)) ? wc_execute_command($command) : '';
        if ($output && substr($output, -1) == "\n") $output = substr($output, 0, -1);

        return array('output' => $output);
    }
}