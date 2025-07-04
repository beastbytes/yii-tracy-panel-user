<?php
/**
 * @var ?string $value
 *  @var TranslatorInterface $translator
 */

use BeastBytes\Yii\Tracy\Panel\User\Panel;
use Yiisoft\Translator\TranslatorInterface;

$translator = $translator->withDefaultCategory(Panel::MESSAGE_CATEGORY);

echo $translator->translate('user.heading.user')
    . ': '
    . ($value === null ? $translator->translate('user.value.guest') : $value)
;