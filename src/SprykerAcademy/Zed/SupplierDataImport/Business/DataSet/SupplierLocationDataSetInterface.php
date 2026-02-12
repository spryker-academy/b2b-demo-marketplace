<?php

namespace SprykerAcademy\Zed\SupplierDataImport\Business\DataSet;

interface SupplierLocationDataSetInterface
{
    public const string COLUMN_ID_SUPPLIER_LOCATION = 'id_supplier_location';
    public const string COLUMN_ID_SUPPLIER = 'id_supplier';
    public const string COLUMN_CITY = 'city';
    public const string COLUMN_COUNTRY = 'country';
    public const string COLUMN_ADDRESS = 'address';
    public const string COLUMN_ZIP_CODE = 'zip_code';
    public const string COLUMN_IS_DEFAULT = 'is_default';
}
