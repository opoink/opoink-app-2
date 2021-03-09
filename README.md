# base-app-2
Is a PHP framework that offers to use VueJs as frontend single site rendering.

Install
-------
    php composer create-project opoink/opoink-app-2


# Generate Component
php opoink --generate=component --location=Vendor_Module::page/header --component-name=component-name
php opoink --g=c --location=Vendor_Module::page/header --cn=component-name
php opoink --g=c --l=Vendor_Module::page/header

php opoink --g=c --l=Vendor_Module::page/header --cn=component-name
php opoink --g=c --l=Vendor_Module --cn=component-name


{
    "component_name": "TestFour",
    "vendor": "Opoink",
    "module": "Base",
    "location": "Opoink\/BaseView\/vue\/components\/test\/TestFour\/TestFour.component",
    "inject_to": [
        {
            "component_name": "DefaultSide",
            "element_id": "default-container",
            "inject_type": "prepend",
            "wrapper": "li"
        }
    ]
}