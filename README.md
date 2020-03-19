# Laravel Log Viewer

## 简介

Laravel Log Viewer 提供了一个基于bootstrap搭建，完美适配PC、平板和移动端的日志查看后台，可自由配置访问路由、权限策略、中间件、导航链接，支持多语言和日志搜索、下载、删除

<p align="center">
<img src="https://raw.githubusercontent.com/gouguoyin/laravel-log-viewer/master/thumb/home-cn.png" width="100%">
<img src="https://raw.githubusercontent.com/gouguoyin/laravel-log-viewer/master/thumb/home-en.png" width="100%">
<img src="https://raw.githubusercontent.com/gouguoyin/laravel-log-viewer/master/thumb/delete.png" width="100%">
</p>

<a name="installation"></a>
## 安装配置

安装 larave-log-viewer

    # 如果只想在开发环境安装请加上 --dev
    composer require gouguoyin/laravel-log-viewer

添加到服务提供者

在 `config/app.php` 的 `providers` 数组中加入

    Gouguoyin\LogViewer\LogViewerServiceProvider::class,

现在你已经可以通过访问`你的域名/logs`进入log-viewer后台，

## 自定义Log Viewer

如果想进行一些自定义操作

运行`php artisan vendor:publish provider="Gouguoyin\LogViewer\LogViewerServiceProvider"`会一次性生成

`app/Providers/LogViewerServiceProvider.php` 服务提供者文件

`configs/log-viewer.php` 配置文件

`resources/lang/cn/log-viewer.php` 中文翻译文件

`resources/lang/en/log-viewer.php` 英文翻译文件

`resources/views/vendor/log-viewer` 视图目录及视图文件

如果只想生成指定分类文件

#### 只生成配置文件

    php artisan vendor:publish provider="Gouguoyin\LogViewer\LogViewerServiceProvider" --tag="log-viewer-config"

#### 只生成服务提供者文件

    php artisan vendor:publish provider="Gouguoyin\LogViewer\LogViewerServiceProvider" --tag="log-viewer-provider"

#### 只生成翻译文件

    php artisan vendor:publish provider="Gouguoyin\LogViewer\LogViewerServiceProvider" --tag="log-viewer-lang"

#### 只生成视图文件

    php artisan vendor:publish provider="Gouguoyin\LogViewer\LogViewerServiceProvider" --tag="log-viewer-views"

通过修改以上文件即可在不修改扩展包的基础上进行自定义操作

## 权限验证
Log Viewer默认路由是 `/logs`， 默认情况下，只能在 `local` 环境下访问。在  `app/Providers/LogViewerServiceProvider.php` 文件中，有一个 `gate` 方法。这里授权控制 非本地 环境中的访问。 你可以根据需要随意修改此门面，以限制对 Log Viewer 的访问：

    /**
     * Register the log-viewer gate.
     *
     * This gate determines who can access log-viewer in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('view-logs', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

> Laravel会自动将 *authenticated* 用户注入到 gate 方法。如果你的应用程序通过其他方法（如IP限制）提供安全，那么用户可能不需要“登录”。因此，你需要将上面的 `function ($user)` 更改为  `function ($user = null)`以屏蔽身份验证。

## 配置说明
| 配置项 | 配置说明 | 可选值 | 默认值 |
| --- | --- | --- | --- | 
| `web_route` |  配置访问路由 |  | logs |
| `web_middleware` |  配置访问中间件 |  | ['web', 'auth'] |
| `web_navbar` |  配置后台右上角导航链接 |  |  |
| `locale_language` |  配置本地化语言 | en:英文、cn:中文 | cn |
| `page_size_menu` |  配置表格每页显示条数下拉菜单 |  | 10, 20, 30, 50, 100 |
| `default_page_size` |  配置表格每页显示条数下拉菜单默认选项 |  | 20 |
| `fix_header` |  配置表格头字段是否固定 | true、false | true |

## 更新日志

### 2020-02-24
* 统一使用DIRECTORY_SEPARATOR常量替代/
* 左侧日志文件支持模糊搜索

### 2020-02-20
* 右上角新增下拉菜单
* 支持自定义授权策略
* 支持自定义视图
* 支持自定义翻译文件
* 支持自定义配置
