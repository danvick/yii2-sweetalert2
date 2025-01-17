<?php

namespace danvick\sweetalert2;

use yii\web\AssetBundle;

/**
 * Class AlertAsset
 *
 * @package danvick\sweetalert2
 */
class SweetAlertAsset extends AssetBundle
{
    /**
     * @var string the directory that contains the source asset files for this asset bundle
     */
    public $sourcePath = '@npm/sweetalert2';

    /**
     * @var array list of JavaScript files that this bundle contains
     */
    public $js = [
        'dist/sweetalert2.min.js',
    ];

    /**
     * @var array list of CSS files that this bundle contains
     */
    public $css = [
        'dist/sweetalert2.min.css',
    ];
	
	public $overrideConfirm = true;
	
	public function init()
    {
        parent::init();
        if ($this->overrideConfirm) {
            self::overrideConfirm();
        }
    }
	
	public static function overrideConfirm()
    {
        \Yii::$app->view->registerJs('
            // workaround for bootstrap modal
			console.log($.fn);
            $.fn.modal.Constructor.prototype.enforceFocus = function () {};
			yii.confirm = function (message, ok, cancel) {
                context = $(this);
                var target = $(this).data();
                console.log($(this).attr("href"));
                Swal.fire({
                    title: message,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "'.\Yii::t('app', 'Yes').'",
                    cancelButtonText: "'.\Yii::t('app', 'No').'",
                }).then((result) => {
					var data = context.data();
						
					if (result.value) {
						if(typeof data.method === "undefined"){
                            window.location.href = context.attr("href");
                            return false;
                        }
                        !ok || ok();
					}
				});          
            }
        ');
    }
}
