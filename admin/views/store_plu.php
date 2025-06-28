<?php defined('EMLOG_ROOT') || exit('access denied!'); ?>
<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger">商店暂不可用，可能是网络问题</div><?php endif ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0 text-gray-800">应用商店 - <?= $sub_title ?></h1>
</div>
<div class="row mb-4 ml-1">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="./store.php">全部应用</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=tpl">模板主题</a></li>
        <li class="nav-item"><a class="nav-link active" href="./store.php?action=plu">扩展插件</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=svip">铁杆免费</a></li>
        <li class="nav-item"><a class="nav-link" href="./store.php?action=mine">我的已购</a></li>
    </ul>
</div>
<div class="d-flex flex-column flex-sm-row justify-content-between mb-4 ml-1">
    <div class="mb-3 mb-sm-0">
        <a href="./store.php?action=plu" class="badge badge-primary m-1 p-2">全部</a>
        <a href="./store.php?action=plu&tag=free" class="badge badge-success m-1 p-2">免费</a>
        <a href="./store.php?action=plu&tag=paid" class="badge badge-warning m-1 p-2">付费</a>
        <a href="./store.php?action=plu&tag=promo" class="badge badge-danger m-1 p-2">优惠</a>
        <a href="./store.php?action=plu&tag=download_top" class="badge badge-light text-primary p-2 small">🔥 下载排行</a>
        <a href="./store.php?action=plu&tag=paid_top" class="badge badge-light text-primary p-2 small">🏆 销量排行</a>
    </div>
    <div class="d-flex mb-3 mb-sm-0">
        <form action="#" method="get" class="mr-sm-2">
            <select name="action" id="plugin-category" class="form-control">
                <?php foreach ($plugin_categories as $k => $v) { ?>
                    <option value="<?= $k; ?>" <?= $sid == $k ? 'selected' : '' ?>><?= $v; ?></option>
                <?php } ?>
            </select>
        </form>
        <form action="./store.php" method="get" class="form-inline ml-2 mr-3">
            <div class="input-group">
                <input type="hidden" name="action" value="plu">
                <input type="text" name="keyword" value="<?= $keyword ?>" class="form-control small" placeholder="搜索插件...">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-success" type="submit">
                        <i class="icofont-search-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="mb-3">
    <?php if (!empty($plugins)): ?>
        <div class="d-flex flex-wrap app-list">
            <?php foreach ($plugins as $k => $v):
                $icon = $v['icon'] ?: "./views/images/plugin.png";
            ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card mb-4 shadow-sm hover-shadow-lg">
                        <a href="#appModal" class="p-1" data-toggle="modal" data-target="#appModal" data-name="<?= $v['name'] ?>" data-url="<?= $v['app_url'] ?>" data-buy-url="<?= $v['buy_url'] ?>">
                            <img class="bd-placeholder-img card-img-top" alt="cover" width="100%" height="225" src="<?= $icon ?>">
                        </a>
                        <div class="card-body">
                            <p class="card-text font-weight-bold">
                                <?php if ($v['top'] === 1): ?>
                                    <span class="badge badge-pink p-1">今日推荐</span>
                                <?php endif; ?>
                                <a href="#appModal" data-toggle="modal" data-target="#appModal" data-name="<?= $v['name'] ?>" data-url="<?= $v['app_url'] ?>" data-buy-url="<?= $v['buy_url'] ?>"><?= $v['name'] ?></a>
                                <?php if ($v['svip']): ?>
                                    <a href="https://www.emlog.net/register" class="badge badge-warning p-1" target="_blank">铁杆免费</a>
                                <?php endif; ?>
                            </p>
                            <p class="card-text text-muted">
                                售价：
                                <?php if ($v['price'] > 0): ?>
                                    <?php if ($v['promo_price'] > 0): ?>
                                        <span style="text-decoration:line-through"><?= $v['price'] ?><small>元</small></span>
                                        <span class="text-danger"><?= $v['promo_price'] ?><small>元</small></span>
                                    <?php else: ?>
                                        <span class="text-danger"><?= $v['price'] ?><small>元</small></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-success">免费</span>
                                <?php endif; ?>
                                <br>
                                <small>
                                    开发者：<a href="./store.php?action=plu&author_id=<?= $v['author_id'] ?>"><?= $v['author'] ?></a><br>
                                    版本号：<?= $v['ver'] ?><br>
                                    安装次数：<?= $v['downloads'] ?><br>
                                    更新时间：<?= $v['update_time'] ?><br>
                                </small>
                            </p>
                            <div class="card-text d-flex justify-content-between">
                                <div class="installMsg"></div>
                                <div>
                                    <?php if (Plugin::isActive($v['alias'])): ?>
                                        <a href="plugin.php" class="btn btn-light">使用中</a>
                                    <?php endif; ?>
                                    <?php if ($v['price'] > 0): ?>
                                        <?php if ($v['purchased'] === true): ?>
                                            <a href="store.php?action=mine" class="btn btn-light">已购买</a>
                                            <a href="#" class="btn btn-success installBtn" data-url="<?= urlencode($v['download_url']) ?>" data-cdn-url="<?= urlencode($v['cdn_download_url']) ?>" data-type="plugin">安装</a>
                                        <?php elseif ($v['svip'] && Register::getRegType() === 2): ?>
                                            <a href="#" class="btn btn-warning installBtn" data-url="<?= urlencode($v['download_url']) ?>" data-cdn-url="<?= urlencode($v['cdn_download_url']) ?>" data-type="plugin">安装</a>
                                        <?php else: ?>
                                            <a href="https://www.emlog.net/order/submit/plugin/<?= $v['id'] ?>" class="btn btn-danger" target="_blank">立即购买</a>
                                        <?php endif ?>
                                    <?php else: ?>
                                        <a href="#" class="btn btn-success installBtn" data-url="<?= urlencode($v['download_url']) ?>" data-cdn-url="<?= urlencode($v['cdn_download_url']) ?>" data-type="plugin">安装</a>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <div class="col-md-12 page my-5"><?= $pageurl ?></div>
    <?php else: ?>
        <div class="col-md-12">
            <div class="alert alert-info">暂未找到结果，应用商店进货中，敬请期待：）</div>
        </div>
    <?php endif ?>
</div>
<div class="modal fade" id="appModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <div>
                    <a href="" class="modal-buy-url text-muted" target="_blank">去官网查看</a>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#menu_store").addClass('active');
        setTimeout(hideActived, 3600);

        $('#plugin-category').on('change', function() {
            var selectedCategory = $(this).val();
            if (selectedCategory) {
                window.location.href = './store.php?action=plu&sid=' + selectedCategory;
            }
        });

        // 滚动加载功能
        let isLoading = false;
        let hasMore = <?= $has_more ? 'true' : 'false' ?>;
        let currentPage = <?= $page ?>;

        function loadMorePlugins() {
            if (isLoading || !hasMore) return;

            isLoading = true;
            const nextPage = currentPage + 1;

            $('.page').html('<div class="text-center"><i class="icofont-spinner-alt-3 icofont-spin"></i> 加载中...</div>');

            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('action', 'ajax_load');
            urlParams.set('type', 'plu');
            urlParams.set('page', nextPage);

            $.ajax({
                url: './store.php',
                type: 'GET',
                data: urlParams.toString(),
                dataType: 'json',
                // 在 AJAX success 回调中添加状态判断
                success: function(response) {
                    if (response.code === 200 && response.data.apps.length > 0) {
                        let html = '';
                        response.data.apps.forEach(function(plugin) {
                            const icon = plugin.icon || './views/images/plugin.png';

                            // 构建按钮HTML
                            let buttonsHtml = '';

                            // 检查使用中状态
                            if (plugin.is_active) {
                                buttonsHtml += '<a href="plugin.php" class="btn btn-light">使用中</a> ';
                            }

                            // 根据价格和权限构建安装/购买按钮
                            if (plugin.price > 0) {
                                if (plugin.purchased === true) {
                                    buttonsHtml += '<a href="store.php?action=mine" class="btn btn-light">已购买</a> ';
                                    buttonsHtml += `<a href="#" class="btn btn-success installBtn" data-url="${encodeURIComponent(plugin.download_url)}" data-cdn-url="${encodeURIComponent(plugin.cdn_download_url)}" data-type="plugin">安装</a>`;
                                } else if (plugin.svip && plugin.user_is_svip) {
                                    buttonsHtml += `<a href="#" class="btn btn-warning installBtn" data-url="${encodeURIComponent(plugin.download_url)}" data-cdn-url="${encodeURIComponent(plugin.cdn_download_url)}" data-type="plugin">安装</a>`;
                                } else {
                                    buttonsHtml += `<a href="https://www.emlog.net/order/submit/plugin/${plugin.id}" class="btn btn-danger" target="_blank">立即购买</a>`;
                                }
                            } else {
                                buttonsHtml += `<a href="#" class="btn btn-success installBtn" data-url="${encodeURIComponent(plugin.download_url)}" data-cdn-url="${encodeURIComponent(plugin.cdn_download_url)}" data-type="plugin">安装</a>`;
                            }

                            html += `
                                <div class="col-md-6 col-lg-3">
                                    <div class="card mb-4 shadow-sm hover-shadow-lg">
                                        <a href="#appModal" class="p-1" data-toggle="modal" data-target="#appModal" data-name="${plugin.name}" data-url="${plugin.app_url}" data-buy-url="${plugin.buy_url}">
                                            <img class="bd-placeholder-img card-img-top" alt="cover" width="100%" height="225" src="${icon}">
                                        </a>
                                        <div class="card-body">
                                            <p class="card-text font-weight-bold">
                                                ${plugin.top === 1 ? '<span class="badge badge-pink p-1">今日推荐</span>' : ''}
                                                <a href="#appModal" data-toggle="modal" data-target="#appModal" data-name="${plugin.name}" data-url="${plugin.app_url}" data-buy-url="${plugin.buy_url}">${plugin.name}</a>
                                                ${plugin.svip ? '<a href="https://www.emlog.net/register" class="badge badge-warning p-1" target="_blank">铁杆免费</a>' : ''}
                                            </p>
                                            <p class="card-text text-muted">
                                                售价：
                                                ${plugin.price > 0 ? 
                                                    (plugin.promo_price > 0 ? 
                                                        `<span style="text-decoration:line-through">${plugin.price}<small>元</small></span> <span class="text-danger">${plugin.promo_price}<small>元</small></span>` : 
                                                        `<span class="text-danger">${plugin.price}<small>元</small></span>`
                                                    ) : 
                                                    '<span class="text-success">免费</span>'
                                                }
                                                <br>
                                                <small>
                                                    开发者：<a href="./store.php?action=plu&author_id=${plugin.author_id}">${plugin.author}</a><br>
                                                    版本号：${plugin.ver}<br>
                                                    安装次数：${plugin.downloads}<br>
                                                    更新时间：${plugin.update_time}<br>
                                                </small>
                                            </p>
                                            <div class="card-text d-flex justify-content-between">
                                                <div class="installMsg"></div>
                                                <div>
                                                    ${buttonsHtml}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        $('.app-list').append(html);
                        currentPage = response.current_page;
                        hasMore = response.has_more;

                        if (hasMore) {
                            $('.page').html('<div class="text-center text-muted">滚动到底部加载更多...</div>');
                        } else {
                            $('.page').html('<div class="text-center text-muted">已加载全部内容</div>');
                        }
                    } else {
                        hasMore = false;
                        $('.page').html('<div class="text-center text-muted">已加载全部内容</div>');
                    }
                },
                error: function() {
                    $('.page').html('<div class="text-center text-danger">加载失败，请重试</div>');
                },
                complete: function() {
                    isLoading = false;
                }
            });
        }

        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                loadMorePlugins();
            }
        });
    });
</script>