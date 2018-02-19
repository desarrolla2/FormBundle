<?php
/**
 * Created by PhpStorm.
 * User: carlos
 * Date: 19/02/18
 * Time: 11:16
 */

namespace Desarrolla2\FormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/form")
 */
class FormController extends \Controller
{
    /**
     * @Route("/ajax", name="_form.select.ajax.js")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function javascriptAction()
    {
        return [];
    }
}