<?php

test('Installer exists.', function () {
  $this->assertTrue(class_exists('\\App\\Core\\Data\\ErrorBag'));

  $this->assertTrue(class_exists('\\App\\Core\\Installer\\Installer'));

  $this->assertTrue(interface_exists('\\App\\Core\\Installer\\InstallerComponent'));

  $this->assertTrue(class_exists('\\App\\Core\\Installer\\ConfigInstaller'));
  $this->assertTrue(class_exists('\\App\\Core\\Installer\\DatabaseInstaller'));
  $this->assertTrue(class_exists('\\App\\Core\\Installer\\UserInstaller'));
});

test('The installer can be created.', function () {
  $installer = new \App\Core\Installer\Installer();

  $this->assertTrue(!empty($installer));
});
