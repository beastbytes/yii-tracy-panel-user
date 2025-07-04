<?php
/**
 * @var \Yiisoft\Translator\TranslatorInterface $translator
 * @var ?string $value
 */
echo $translator->translate('user.heading.user', category: 'tracy-user')
    . ': '
    . ($value === null ? $translator->translate('user.value.guest', category: 'tracy-user') : $value)
;