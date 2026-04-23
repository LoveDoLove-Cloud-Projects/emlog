<?php
/**
 * 自定义404页面
 */
defined('EMLOG_ROOT') || exit('access denied!');
?>
<!doctype html>
<html lang="<?= _currentHtmlLang() ?>" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= _langTpl('page_not_found_title') ?></title>
    <link href="<?= TEMPLATE_URL ?>css/style.css?t=<?= Option::EMLOG_VERSION_TIMESTAMP ?>" rel="stylesheet" type="text/css" />
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: var(--bodyBground);
            color: var(--fontColor);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background-color: var(--conBgcolor);
            border-radius: var(--marRadius);
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.05);
            text-align: center;
            max-width: 600px;
            width: 90%;
            /* 黄金比例 1.618:1 */
            aspect-ratio: 1.618 / 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 20px;
            padding: 2rem;
            box-sizing: border-box;
        }
        .error-code {
            font-size: clamp(4rem, 15vw, 8rem);
            font-weight: 800;
            margin: 0;
            line-height: 1;
            color: var(--lightColor);
            opacity: 0.25;
            letter-spacing: -2px;
        }
        .error-msg {
            font-size: clamp(1.1rem, 4vw, 1.5rem);
            font-weight: 600;
            margin: 1rem 0 2rem;
        }
        .btn-home {
            display: inline-block;
            background-color: var(--buttonBgColor);
            color: var(--buttonTextColor) !important;
            border: 1px solid var(--buttonBorderColor);
            padding: 0.75rem 2.5rem;
            border-radius: var(--marRadius);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        .btn-home:hover {
            background-color: var(--aHoverColor);
            border-color: var(--aHoverColor);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 480px) {
            .card {
                /* 在极小屏幕上如果内容过多可取消固定比例以防溢出，但通常404内容很少，保持比例即可 */
                aspect-ratio: 1.618 / 1;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 class="error-code">404</h1>
        <p class="error-msg"><?= _langTpl('page_not_found_title') ?></p>
        <a href="<?= BLOG_URL ?>" class="btn-home"><?= _langTpl('back_to_home') ?></a>
    </div>
    <script src="<?= TEMPLATE_URL ?>js/jquery.min.3.5.1.js?v=<?= Option::EMLOG_VERSION_TIMESTAMP ?>"></script>
    <script src="<?= TEMPLATE_URL ?>js/common_tpl.js?t=<?= Option::EMLOG_VERSION_TIMESTAMP ?>"></script>
</body>
</html>
