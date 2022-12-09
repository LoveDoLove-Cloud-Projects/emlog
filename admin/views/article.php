<?php
if (!defined('EMLOG_ROOT')) {
	exit('error!');
}
$isdraft = $draft ? '&draft=1' : '';
$isDisplaySort = !$sid ? "style=\"display:none;\"" : '';
$isDisplayTag = !$tagId ? "style=\"display:none;\"" : '';
$isDisplayUser = !$uid ? "style=\"display:none;\"" : '';
?>
<?php if (isset($_GET['active_del'])): ?>
    <div class="alert alert-success">删除成功</div><?php endif ?>
<?php if (isset($_GET['active_up'])): ?>
    <div class="alert alert-success">置顶成功</div><?php endif ?>
<?php if (isset($_GET['active_down'])): ?>
    <div class="alert alert-success">取消置顶成功</div><?php endif ?>
<?php if (isset($_GET['error_a'])): ?>
    <div class="alert alert-danger">请选择要处理的文章</div><?php endif ?>
<?php if (isset($_GET['error_b'])): ?>
    <div class="alert alert-danger">请选择要执行的操作</div><?php endif ?>
<?php if (isset($_GET['active_post'])): ?>
    <div class="alert alert-success">发布成功</div><?php endif ?>
<?php if (isset($_GET['active_move'])): ?>
    <div class="alert alert-success">移动成功</div><?php endif ?>
<?php if (isset($_GET['active_change_author'])): ?>
    <div class="alert alert-success">更改作者成功</div><?php endif ?>
<?php if (isset($_GET['active_hide'])): ?>
    <div class="alert alert-success">转入草稿箱成功</div><?php endif ?>
<?php if (isset($_GET['active_savedraft'])): ?>
    <div class="alert alert-success">草稿保存成功</div><?php endif ?>
<?php if (isset($_GET['active_savelog'])): ?>
    <div class="alert alert-success">保存成功</div><?php endif ?>
<?php if (isset($_GET['active_ck'])): ?>
    <div class="alert alert-success">文章审核成功</div><?php endif ?>
<?php if (isset($_GET['active_unck'])): ?>
    <div class="alert alert-success">文章驳回成功</div><?php endif ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $draft ? '草稿箱' : '文章' ?></h1>
    <a href="./article.php?action=write" class="btn btn-sm btn-success shadow-sm mt-4"><i class="icofont-pencil-alt-5"></i> 写新文章</a>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="row justify-content-between">
            <div class="form-inline">
                <div id="f_t_sort" class="mx-1">
                    <select name="bysort" id="bysort" onChange="selectSort(this);" class="form-control">
                        <option value="" selected="selected">按分类查看</option>
						<?php
						foreach ($sorts as $key => $value):
							if ($value['pid'] != 0) {
								continue;
							}
							$flg = $value['sid'] == $sid ? 'selected' : '';
							?>
                            <option value="<?= $value['sid'] ?>" <?= $flg ?>><?= $value['sortname'] ?></option>
							<?php
							$children = $value['children'];
							foreach ($children as $key):
								$value = $sorts[$key];
								$flg = $value['sid'] == $sid ? 'selected' : '';
								?>
                                <option value="<?= $value['sid'] ?>" <?= $flg ?>>&nbsp; &nbsp; &nbsp; <?= $value['sortname'] ?></option>
							<?php
							endforeach;
						endforeach;
						?>
                        <option value="-1" <?php if ($sid == -1) echo 'selected' ?>>未分类</option>
                    </select>
                </div>
				<?php if (User::haveEditPermission() && count($user_cache) > 1): ?>
                    <div id="f_t_user" class="mx-1">
                        <select name="byuser" id="byuser" onChange="selectUser(this);" class="form-control">
                            <option value="" selected="selected">按作者查看</option>
							<?php
							foreach ($user_cache as $key => $value):
								$flg = $key == $uid ? 'selected' : '';
								?>
                                <option value="<?= $key ?>" <?= $flg ?>><?= $value['name'] ?></option>
							<?php endforeach ?>
                        </select>
                    </div>
				<?php endif ?>
            </div>
            <form action="article.php" method="get">
                <div class="form-inline search-inputs-nowrap">
                    <input type="text" name="keyword" class="form-control m-1 small" placeholder="查找文章..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-sm btn-success" type="submit">
                            <i class="icofont-search-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <form action="article.php?action=operate_log" method="post" name="form_log" id="form_log">
            <input type="hidden" name="draft" value="<?= $draft ?>">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover dataTable no-footer">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"/></th>
                        <th>标题</th>
                        <th><a href="article.php?sortComm=<?= $sortComm . $sorturl ?>">评论</a></th>
                        <th><a href="article.php?sortView=<?= $sortView . $sorturl ?>">浏览</a></th>
                        <th>作者</th>
                        <th>分类</th>
                        <th><a href="article.php?sortDate=<?= $sortDate . $sorturl ?>">时间</a></th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ($logs as $key => $value):
						$sortName = isset($sorts[$value['sortid']]['sortname']) ? $sorts[$value['sortid']]['sortname'] : '未知分类';
						$sortName = $value['sortid'] == -1 ? '未分类' : $sortName;
						$author = isset($user_cache[$value['author']]['name']) ? $user_cache[$value['author']]['name'] : '未知作者';
						$author_role = isset($user_cache[$value['author']]['role']) ? $user_cache[$value['author']]['role'] : '未知角色';
						?>
                        <tr>
                            <td style="width: 20px;"><input type="checkbox" name="blog[]" value="<?= $value['gid'] ?>" class="ids"/></td>
                            <td>
                                <a href="article.php?action=edit&gid=<?= $value['gid'] ?>"><?= $value['title'] ?></a><br>
								<?php if ($value['top'] == 'y'): ?><span class="badge small badge-success">首页置顶</span><?php endif ?>
								<?php if ($value['sortop'] == 'y'): ?><span class="badge small badge-info">分类置顶</span><?php endif ?>
								<?php if ($value['timestamp'] > time()): ?><span class="badge small badge-warning">定时发布</span><?php endif ?>
								<?php if ($value['password']): ?><span class="small">🔒</span><?php endif ?>
								<?php if (!$draft && $value['checked'] == 'n'): ?><span class="badge small badge-danger">待审核</span><?php endif ?>
                            </td>
                            <td><a href="comment.php?gid=<?= $value['gid'] ?>" class="badge badge-info"><?= $value['comnum'] ?></a></td>
                            <td><a href="<?= Url::log($value['gid']) ?>" class="badge badge-secondary" target="_blank"><?= $value['views'] ?></a></td>
                            <td><a href="article.php?uid=<?= $value['author'] . $isdraft ?>"><?= $author ?></a></td>
                            <td><a href="article.php?sid=<?= $value['sortid'] . $isdraft ?>"><?= $sortName ?></a></td>
                            <td class="small"><?= $value['date'] ?></td>
                            <td>
								<?php if (!$draft && User::haveEditPermission() && $value['checked'] == 'n'): ?>
                                    <a class="badge badge-success"
                                       href="article.php?action=operate_log&operate=check&gid=<?= $value['gid'] ?>&token=<?= LoginAuth::genToken() ?>">审核</a>
								<?php elseif (!$draft && User::haveEditPermission() && $author_role == User::ROLE_WRITER): ?>
                                    <a class="badge badge-warning"
                                       href="article.php?action=operate_log&operate=uncheck&gid=<?= $value['gid'] ?>&token=<?= LoginAuth::genToken() ?>">驳回</a>
								<?php endif ?>
								<?php if ($draft): ?>
                                    <a href="javascript: em_confirm(<?= $value['gid'] ?>, 'draft', '<?= LoginAuth::genToken() ?>');" class="badge badge-danger">删除</a>
								<?php else: ?>
                                    <a href="javascript: em_confirm(<?= $value['gid'] ?>, 'article', '<?= LoginAuth::genToken() ?>');" class="badge badge-danger">删除</a>
								<?php endif ?>
                            </td>
                        </tr>
					<?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <input name="token" id="token" value="<?= LoginAuth::genToken() ?>" type="hidden"/>
            <input name="operate" id="operate" value="" type="hidden"/>
            <div class="form-inline">
				<?php if (User::haveEditPermission()): ?>
                    <select name="top" id="top" onChange="changeTop(this);" class="form-control m-1">
                        <option value="" selected="selected">置顶</option>
                        <option value="top">首页置顶</option>
                        <option value="sortop">分类置顶</option>
                        <option value="notop">取消置顶</option>
                    </select>
				<?php endif ?>
                <select name="sort" id="sort" onChange="changeSort(this);" class="form-control m-1">
                    <option value="" selected="selected">移动到分类</option>
					<?php
					foreach ($sorts as $key => $value):
						if ($value['pid'] != 0) {
							continue;
						}
						?>
                        <option value="<?= $value['sid'] ?>"><?= $value['sortname'] ?></option>
						<?php
						$children = $value['children'];
						foreach ($children as $key):
							$value = $sorts[$key];
							?>
                            <option value="<?= $value['sid'] ?>">&nbsp; &nbsp;
                                &nbsp; <?= $value['sortname'] ?></option>
						<?php
						endforeach;
					endforeach;
					?>
                    <option value="-1">未分类</option>
                </select>
				<?php if (User::haveEditPermission() && count($user_cache) > 1): ?>
                    <select name="author" id="author" onChange="changeAuthor(this);" class="form-control m-1">
                        <option value="" selected="selected">更改作者</option>
						<?php foreach ($user_cache as $key => $val): ?>
                            <option value="<?= $key ?>"><?= $val['name'] ?></option>
						<?php endforeach ?>
                    </select>
				<?php endif ?>

                <div class="btn-group btn-group-sm" role="group">
					<?php if ($draft): ?>
                        <a href="javascript:logact('pub');" class="btn btn-sm btn-success">发布</a>
					<?php else: ?>
                        <a href="javascript:logact('hide');" class="btn btn-sm btn-success">放入草稿箱</a>
					<?php endif ?>
                    <a href="javascript:logact('del');" class="btn btn-sm btn-danger">删除</a>
                </div>
            </div>
        </form>
        <div class="page"><?= $pageurl ?> (有 <?= $logNum ?> 篇<?= $draft ? '草稿' : '文章' ?>)</div>
    </div>
</div>
<script>
    $("#menu_category_content").addClass('active');
    $("#menu_content").addClass('show');
    $("#menu_<?= $draft ? 'draft' : 'log' ?>").addClass('active');
    setTimeout(hideActived, 2600);

    $(document).ready(function () {
        $("#f_t_tag").click(function () {
            $("#f_tag").toggle();
            $("#f_sort").hide();
            $("#f_user").hide();
        });
    });

    function logact(act) {
        if (getChecked('ids') == false) {
            swal("", "请选择要操作的文章!", "info");
            return;
        }

        if (act == 'del') {
            swal({
                title: '确定要删除所选文章吗',
                text: '删除后可能无法恢复',
                icon: 'warning',
                buttons: ['取消', '确定'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $("#operate").val(act);
                    $("#form_log").submit();
                }
            });
            return;
        }
        $("#operate").val(act);
        $("#form_log").submit();
    }

    function changeSort(obj) {
        if (getChecked('ids') == false) {
            swal("", "请选择要操作的文章!", "info");
            return;
        }
        if ($('#sort').val() == '') return;
        $("#operate").val('move');
        $("#form_log").submit();
    }

    function changeAuthor(obj) {
        if (getChecked('ids') == false) {
            swal("", "请选择要操作的文章!", "info");
            return;
        }
        if ($('#author').val() == '') return;
        $("#operate").val('change_author');
        $("#form_log").submit();
    }

    function changeTop(obj) {
        if (getChecked('ids') == false) {
            swal("", "请选择要操作的文章!", "info");
            return;
        }
        if ($('#top').val() == '') return;
        $("#operate").val(obj.value);
        $("#form_log").submit();
    }

    function selectSort(obj) {
        window.open("./article.php?sid=" + obj.value + "<?= $isdraft?>", "_self");
    }

    function selectUser(obj) {
        window.open("./article.php?uid=" + obj.value + "<?= $isdraft?>", "_self");
    }
</script>
