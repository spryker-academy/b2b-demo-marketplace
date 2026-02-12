<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataSet;

interface SupplierLocationDataSetInterface
{
    public const COLUMN_ID_SUPPLIER_LOCATION = 'id_supplier_location';
    public const COLUMN_ID_SUPPLIER = 'id_supplier';
    public const COLUMN_CITY = 'city';
    public const COLUMN_COUNTRY = 'country';
    public const COLUMN_ADDRESS = 'address';
    public const COLUMN_ZIP_CODE = 'zip_code';
    public const COLUMN_IS_DEFAULT = 'is_default';
}
