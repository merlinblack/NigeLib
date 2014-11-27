<?php

// Settings for adLDAP to connect to Active Directory.

return array(
    'domain_controllers' => array( 'pdc.mailcall.com.au','sdc.mailcall.com.au' ),
    'account_suffix' => '@mailcall.com.au',
    'base_dn'        => 'DC=mailcall,DC=com,DC=au',
);
