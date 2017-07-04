## Krait's TiAmo ##
 这是一款采用兼容性开发的 Typecho 主题，基于chivalric模板开发，目前适合个人博客,多用户的博客可能会出现一些出错。应用本主题时同时需要 Ryan 的插件 [iThumb][1] 和 mufeng 的插件 PostFormat 七其中 PostFormat 与部分代码集合成了 TiAmo 配套插件 timaos ,我已经把主题和需要的插件一起打包开源。

## 主题 ##
**作者**: [权那他][2] 就是我!

**引源**基于chivalric模板开发

## 下载 ##
Github TiAmo : [https://github.com/kraity/TiAmo][15]

文件树
`TiAmo`  主题文件
`tiamos` TiAmo 主题配置插件

**切记**需要插件`iThumb` ,若不会此插件,那得相应的文章类型每次添加自定义字符`thumbUrl`

## 演示站点 ##
此站: [那他 - Krait.cn][16]

## 更新历史 ##
2017-07-4 日版本v1.0.1

- 标签`.single-post-content .post-title`去掉属性`font-family: Georgia,serif;` 以此解决没有`Georgia`的font文件导致标题显示空白错误。
- 标签`post-meta `重写,新增作者的小头像,和评论状况以及日期。
- 新增标签`language-unknown `的属性,优先权。
- `comments.php` 补上标签`<div id="comments"> `解决后台开启评论保护导致无法评论。
- 跟着作者GIT地址的错误。

2017-07-2 日开源 v1.0

- 开源

 
[2]: https://krait.cn
[15]: https://github.com/kraity/TiAmo
[16]: https://krait.cn
