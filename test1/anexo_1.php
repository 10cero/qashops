<?php

class Product
{
/*
 *		MÉTODO ORIGINAL: stock()
 *      Qashop
 *
 *

    public static function stock(
        $productId,
        $quantityAvailable,
        $cache = false,
        $cacheDuration = 60,
        $securityStockConfig = null
    ) {
        if ($cache) {
            // Obtenemos el stock bloqueado por pedidos en curso
            $ordersQuantity = OrderLine::getDb()->cache(function ($db) use ($productId) {
                return OrderLine::find()->select('SUM(quantity) as quantity')->joinWith('order')->where("(order.status = '" . Order::STATUS_PENDING . "' OR order.status = '" . Order::STATUS_PROCESSING . "' OR order.status = '" . Order::STATUS_WAITING_ACCEPTANCE . "') AND order_line.product_id = $productId")->scalar();
            }, $cacheDuration);

            // Obtenemos el stock bloqueado
            $blockedStockQuantity = BlockedStock::getDb()->cache(function ($db) use ($productId) {
                return BlockedStock::find()->select('SUM(quantity) as quantity')->joinWith('shoppingCart')->where("blocked_stock.product_id = $productId AND blocked_stock_date > '" . date('Y-m-d H:i:s') . "' AND (shopping_cart_id IS NULL OR shopping_cart.status = '" . ShoppingCart::STATUS_PENDING . "')")->scalar();
            }, $cacheDuration);
        } else {
            // Obtenemos el stock bloqueado por pedidos en curso
            $ordersQuantity = OrderLine::find()->select('SUM(quantity) as quantity')->joinWith('order')->where("(order.status = '" . Order::STATUS_PENDING . "' OR order.status = '" . Order::STATUS_PROCESSING . "' OR order.status = '" . Order::STATUS_WAITING_ACCEPTANCE . "') AND order_line.product_id = $productId")->scalar();

            // Obtenemos el stock bloqueado
            $blockedStockQuantity = BlockedStock::find()->select('SUM(quantity) as quantity')->joinWith('shoppingCart')->where("blocked_stock.product_id = $productId AND blocked_stock_to_date > '" . date('Y-m-d H:i:s') . "' AND (shopping_cart_id IS NULL OR shopping_cart.status = '" . ShoppingCart::STATUS_PENDING . "')")->scalar();
        }

        // Calculamos las unidades disponibles
        if (isset($ordersQuantity) || isset($blockedStockQuantity)) {
            if ($quantityAvailable >= 0) {
                $quantity = $quantityAvailable - @$ordersQuantity - @$blockedStockQuantity;
                if (!empty($securityStockConfig)) {
                    $quantity = ShopChannel::applySecurityStockConfig(
                        $quantity,
                        @$securityStockConfig->mode,
                        @$securityStockConfig->quantity
                    );
                }
                return $quantity > 0 ? $quantity : 0;
            } elseif ($quantityAvailable < 0) {
                return $quantityAvailable;
            }
        } else {
            if ($quantityAvailable >= 0) {
                if (!empty($securityStockConfig)) {
                    $quantityAvailable = ShopChannel::applySecurityStockConfig(
                        $quantityAvailable,
                        @$securityStockConfig->mode,
                        @$securityStockConfig->quantity
                    );
                }
                $quantityAvailable = $quantityAvailable > 0 ? $quantityAvailable : 0;
            }
            return $quantityAvailable;
        }
        return 0;
    }

*/

/*	
 *		MÉTODO REFACTORIZADO: stock()
 *		Autor: Francisco José Torres Álvarez
 *		Fecha: 21/01/2017 11:30
 *
 */

    public static function stock(
        $productId,
        $quantityAvailable,
        $cache = false,
        $cacheDuration = 60,
        $securityStockConfig = null
    ) {
		// Obtenemos el stock bloqueado por pedidos pendientes
		$blockedQuantityPendingOrder = (int)self::blockedStockQuantityPendingOrder($productId,$cache,$cacheDuration);
		
		// Obtenemos el stock bloqueado por carritos en curso
		$blockedQuantityCurrentCart = (int)self::blockedStockQuantityCurrentCart($productId,$cache,$cacheDuration);
		
		// Obtenemos la cantidad disponible considerando los productos bloqueados
		$quantity = $quantityAvailable - $blockedQuantityPendingOrder - $blockedQuantityCurrentCart;
		
		if($quantity >= 0){
			
			// Revisamos si son aplicables medidas de seguridad de stock en los canales según la congifuración
			$quantity = ShopChannel::applySecurityStockConfig(
                        $quantity,
                        @$securityStockConfig->mode,
                        @$securityStockConfig->quantity
                    );
		}

		return $quantity > 0 ? $quantity : 0;
    }
	

/*	
 *		MÉTODO AUXILIAR: blockedStockQuantityPendingOrder()
 *      Descripción: Obtenemos el stock bloqueado de un producto por pedidos pendientes
 *		Autor: Francisco José Torres Álvarez
 *		Fecha: 21/01/2017 11:30
 *
 */

	
	public static function blockedStockQuantityPendingOrder(
        $productId,
        $cache = false,
        $cacheDuration = 60
	) {
        if ($cache) {
            $ordersQuantity = OrderLine::getDb()->cache(function ($db) use ($productId) {
                return OrderLine::find()->select('SUM(quantity) as quantity')->joinWith('order')->where("(order.status = '" . Order::STATUS_PENDING . "' OR order.status = '" . Order::STATUS_PROCESSING . "' OR order.status = '" . Order::STATUS_WAITING_ACCEPTANCE . "') AND order_line.product_id = $productId")->scalar();
            }, $cacheDuration);
		} else {
            $ordersQuantity = OrderLine::find()->select('SUM(quantity) as quantity')->joinWith('order')->where("(order.status = '" . Order::STATUS_PENDING . "' OR order.status = '" . Order::STATUS_PROCESSING . "' OR order.status = '" . Order::STATUS_WAITING_ACCEPTANCE . "') AND order_line.product_id = $productId")->scalar();
		}
		
		return $ordersQuantity;
	}

/*	
 *		MÉTODO AUXILIAR: blockedStockQuantityCurrentCart()
 *      Descripción: Obtenemos el stock bloqueado de un producto por carritos en curso
 *		Autor: Francisco José Torres Álvarez
 *		Fecha: 21/01/2017 11:30
 *
 */

 public static function blockedStockQuantityCurrentCart(
        $productId,
        $cache = false,
        $cacheDuration = 60
	) {
        if ($cache) {
            $cartsQuantity = BlockedStock::getDb()->cache(function ($db) use ($productId) {
                return BlockedStock::find()->select('SUM(quantity) as quantity')->joinWith('shoppingCart')->where("blocked_stock.product_id = $productId AND blocked_stock_date > '" . date('Y-m-d H:i:s') . "' AND (shopping_cart_id IS NULL OR shopping_cart.status = '" . ShoppingCart::STATUS_PENDING . "')")->scalar();
            }, $cacheDuration);
		} else {
            $cartsQuantity = BlockedStock::find()->select('SUM(quantity) as quantity')->joinWith('shoppingCart')->where("blocked_stock.product_id = $productId AND blocked_stock_to_date > '" . date('Y-m-d H:i:s') . "' AND (shopping_cart_id IS NULL OR shopping_cart.status = '" . ShoppingCart::STATUS_PENDING . "')")->scalar();
		}
		
		return $cartsQuantity;
	}
	
	
}

