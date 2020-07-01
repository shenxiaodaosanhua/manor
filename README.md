## 美生庄园

### 安装
`composer install`

### 生产环境部署
`composer install --no-dev`

### 添加配置文件
`cp .env.example .env`

### 安装后台
`php artisan admin:install`

### 添加后台扩展
> 填充数据 
>>` php artisan db:seed`

> 发布资源
>> `php artisan vendor:publish --provider="Encore\Admin\AdminServiceProvider"`
