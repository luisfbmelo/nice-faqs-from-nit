<?php
namespace NitFAQ\Interfaces;

// Declare the interface 'iTemplate'
interface iWithTax
{
    public function getTaxs(string $slug): iTax;
    public function setTaxs(string $slug, iTax $targetTax);
}
