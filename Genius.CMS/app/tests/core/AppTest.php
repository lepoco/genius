<?php

test('PHP version is compatible.', function () {
  $this->assertTrue(defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION >= 8);
});

test('ErrorHandler may be registered.', function () {
  $this->assertTrue(class_exists('\\App\\Core\\Utils\\ErrorHandler'));
  $this->assertTrue(\App\Core\Utils\ErrorHandler::register());
});

test('Configuration class exists', function () {
  $this->assertTrue(class_exists('\\App\\Common\\Config'));
});

test('Application instance can be created.', function () {
  $this->assertTrue(!empty(new \App\Common\App()));
});
