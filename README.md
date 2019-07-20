 ## 说明
 > 一个基于Web Console实现的laravel web-console扩展。
![我的效果图](https://github.com/wanghouting/webconsole/blob/master/thumb/thumb.png)
 ---
 
 
 ### 安装
```
composer require wanghouting/web-console
```
### 发布配置文件
```
php artisan vendor:publish --provider="WebConsole\Extension\LaravelServiceProvider"
```
### 访问

```
http://127.0.0.1/admin/console
```

### 修改配置

> 配置文件在config/webconsole.php 下，如有需要，可以修改相关配置