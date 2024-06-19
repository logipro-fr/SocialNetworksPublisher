<?php

return function (array $context) {
    $kernelClass = $_ENV['KERNEL_CLASS'];
    return new $kernelClass($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
