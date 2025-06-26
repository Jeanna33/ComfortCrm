<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
        ]);

        $currentLang = Yii::$app->language;
        $languages = [
            'en-US' => 'English',
            'ru-RU' => 'Русский',
            // добавьте другие языки
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ms-auto'], // Заменил ml-auto на ms-auto для Bootstrap 5
            'items' => [
                ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index'], 'options' => ['class' => 'nav-item'], 'linkOptions' => ['class' => 'nav-link']],
                ['label' => Yii::t('app', 'About'), 'url' => ['/site/about'], 'options' => ['class' => 'nav-item'], 'linkOptions' => ['class' => 'nav-link']],
                ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact'], 'options' => ['class' => 'nav-item'], 'linkOptions' => ['class' => 'nav-link']],
                ['label' => Yii::t('app', 'DashBoard'), 'url' => ['/site/dashboard'], 'options' => ['class' => 'nav-item'], 'linkOptions' => ['class' => 'nav-link']],
                Yii::$app->user->isGuest
                    ? ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login'], 'options' => ['class' => 'nav-item'], 'linkOptions' => ['class' => 'nav-link']]
                    : '<li class="nav-item">'
                    . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
                    . Html::submitButton(
                        Yii::t('app', 'Logout') . ' (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link nav-link']
                    )
                    . Html::endForm()
                    . '</li>',
                [
                    'label' => '<i class="fas fa-globe"></i> ' . $languages[$currentLang],
                    'encode' => false,
                    'options' => ['class' => 'nav-item dropdown'],
                    'linkOptions' => ['class' => 'nav-link dropdown-toggle', 'data-bs-toggle' => 'dropdown'], // Заменил data-toggle на data-bs-toggle для Bootstrap 5
                    'items' => array_map(function ($code, $name) use ($currentLang) {
                        return [
                            'label' => $name,
                            'url' => ['/site/change-language', 'lang' => $code],
                            'options' => [
                                'class' => $currentLang === $code ? 'dropdown-item active' : 'dropdown-item'
                            ]
                        ];
                    }, array_keys($languages), $languages)
                ]
            ]
        ]);
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">© My Company <?= date('Y') ?></div>
                <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>