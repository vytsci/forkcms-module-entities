# Fork CMS Localization
## Introduction
Module was created to make DB records into entities. If you want to create a simple module with simple data you can
avoid using this thing, but if your module has larger data this thing will be a life savior. This is practically created
to make things easier while Fork CMS team integrates Doctrine 2 into their CMS.

## Requirements
* Core: Fork CMS 3.9.4
* Module: [Entities](https://github.com/vytenizs/forkcms-module-entities)

## Usage
### Database
Practically any table works if you configure this module for your needs. Main rule that table must have ID field by
default, but you can change that.

### Action files
#### Namespaces
First of all we need to load proper namespaces

```
use Common\Modules\Localization\Entity;
```

#### Object
We must create our record object, here is an example:

```
<?php
namespace Common\Modules\Addresses;
use Common\Modules\Entities\Entity;
/**
 * Class Address
 * @package Common\Modules\Addresses
 */
class Address extends Entity
{
    /**
     * Table name of addresses
     *
     * @var string
     */
    protected $_table = 'addresses';
    /**
     * Query to select single address by ID
     *
     * @var string
     */
    protected $_query = 'SELECT a.* FROM addresses AS a WHERE a.id = ?';
    /**
     * List of table columns
     *
     * @var array
     */
    protected $_columns = array(
        'country',
        'city',
        'address'
    );
    /**
     * ISO code of a country
     *
     * @var string
     */
    protected $country;
    /**
     * Name of a city
     *
     * @var string
     */
    protected $city;
    /**
     * Full address
     *
     * @var string
     */
    protected $address;
    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }
    /**
     * @param $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    /**
     * @param $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }
    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }
}
```

#### Usage examples
I will add some examples, how to use this thing.

##### Setting object and storing data

```
$address = new Address();
$address->setCountry('Lithuania');
$address->setCity('Kaunas');
```

##### Saving data

```
$address->save();
```

##### Converting to an array

```
$addressArray = $address->toArray();
```

### Loading with parameters

```
$address = new Address(array($this->getParameter('address_id', 'int')));
```

or

```
$address = new Address();
$address->load(array($this->getParameter('address_id', 'int')));
```

## Issues
If you are having any issues, please create issue at [Github](https://github.com/vytenizs/forkcms-module-localization/issues).
Or contact me directly. Thank you.

## Contacts

* e-mail: info@vytsci.lt
* skype: vytenizs
