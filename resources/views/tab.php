<?php
/**
 * @var \Yiisoft\Translator\TranslatorInterface $translator
 * @var ?string $value
 */
echo $translator->translate('heading.user.user', category: 'tracy-user')
    . ': '
    . ($value === null ? $translator->translate('value.user.guest', category: 'tracy-user') : $value)
;