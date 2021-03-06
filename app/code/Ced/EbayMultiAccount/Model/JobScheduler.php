<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category  Ced
 * @package   Ced_EbayMultiAccount
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */
namespace Ced\EbayMultiAccount\Model;

class JobScheduler extends \Magento\Framework\Model\AbstractModel
{
    const ADDITEM = 'AddItem';
    const ADDFIXEDPRICEITEM = 'AddFixedPriceItem';
    const REVISEINVENTORYSTATUS = 'ReviseInventoryStatus';
    const REVISEITEM = 'ReviseItem';

    public function _construct()
    {
        $this->_init('Ced\EbayMultiAccount\Model\ResourceModel\JobScheduler');

    }
}
