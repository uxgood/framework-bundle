<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UxGood\Bundle\FrameworkBundle\DataCollector;

use UxGood\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\DataCollector\RouterDataCollector as BaseRouterDataCollector;

/**
 * RouterDataCollector.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class RouterDataCollector extends BaseRouterDataCollector
{
    public function guessRoute(Request $request, $controller)
    {
        if (\is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof RedirectController) {
            return $request->attributes->get('_route');
        }

        return parent::guessRoute($request, $controller);
    }
}
