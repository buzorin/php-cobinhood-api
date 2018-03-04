# PHP Cobinhood API
PHP Cobinhood API is a simple to use API-client in PHP for [Cobinhood](https://cobinhood.com) The World's First ZERO Trading Fees Cryptocurrency Exchange.

> NOTE: COBINHOOD IS CURRENTLY UNDER HEAVY DEVELOPMENT,
> APIs ARE SUBJECT TO CHANGE WITHOUT PRIOR NOTICE

[Cobinhood API documentation](https://cobinhood.github.io/api-public)

### Table of contents

* [Getting started using Composer](#getting-started-using-composer)
  + [Installation](#installation)
  + [Main setup](#main-setup)
* [Getting started without Composer](#getting-started-without-composer)
  + [Installation](#installation-1)
  + [Main setup](#main-setup-1)
* [Public API](#public-api)
  + [Get the latest price of a symbol](#get-the-latest-price-of-a-symbol)
  + [Get depth of a symbol](#get-depth-of-a-symbol)
  + [Get all currencies](#get-all-currencies)
  + [Get info for all trading pairs](#get-info-for-all-trading-pairs)
  + [Get trading statistics](#get-trading-statistics)
  + [Get ticker of a symbol](#get-ticker-of-a-symbol)
  + [Get tickers of all symbols](#get-tickers-of-all-symbols)
  + [Get recent trades of a symbol](#get-recent-trades-of-a-symbol)
  + [Get candles of a symbol](#get-candles-of-a-symbol)
  + [Get server time](#get-server-time)
  + [Get server information](#get-server-information)
* [Trading API](#trading-api)
  + [Place a LIMIT BUY order](#place-a-limit-buy-order)
  + [Place a LIMIT SELL order](#place-a-limit-sell-order)
  + [Place a MARKET BUY order](#place-a-market-buy-order)
  + [Place a MARKET SELL order](#place-a-market-sell-order)
  + [Get an order's status](#get-an-orders-status)
  + [Cancel an order](#cancel-an-order)
  + [Modify an order](#modify-an-order)
  + [Get open orders of a symbol](#get-open-orders-of-a-symbol)
  + [Get all open orders](#get-all-open-orders)
  + [Get order's trades](#get-orders-trades)
  + [Get order history of a symbol](#get-order-history-of-a-symbol)
  + [Get all order history](#get-all-order-history)
* [Wallet API](#wallet-api)
  + [Get wallet balances](#get-wallet-balances)
  + [Get balance history of a currency](#get-balance-history-of-a-currency)
  + [Get all balance history](#get-all-balance-history)
  + [Get deposit addresses of a currency](#get-deposit-addresses-of-a-currency)
  + [Get all deposit addresses](#get-all-deposit-addresses)
  + [Get deposit's status](#get-deposits-status)
  + [Get all deposits](#get-all-deposits)
  + [Get withdrawal addresses of a currency](#get-withdrawal-addresses-of-a-currency)
  + [Get all withdrawal addresses](#get-all-withdrawal-addresses)
  + [Get withdrawal's status](#get-withdrawals-status)
  + [Get all withdrawals](#get-all-withdrawals)


## Getting started using Composer

#### Installation
```sh
composer require buzorin/php-cobinhood-api @dev
```

#### Main setup
```php
require "vendor/autoload.php";
$cobinhood = new Cobinhood\API_client("<api key>");
```

## Getting started without Composer

#### Installation
Just download [repository](https://github.com/buzorin/php-cobinhood-api/archive/master.zip) and place single php-cobinhood-api.php file from there wherever you want at your webserver.

#### Main setup
```php
require "php-cobinhood-api.php";
$cobinhood = new Cobinhood\API_client("<api key>");
```

## Public API

#### Get the latest symbol price
```php
$last_price = $cobinhood->get_last_price("COB-BTC");
if (!$last_price["error"]) {
    echo $last_price;
    // 0.00001601
}
```

#### Get depth of a symbol
```php
$limit = 5; // Optional. Defaults to 50 if not specified, if limit is 0, it means to fetch the whole order book.

$depth = $cobinhood->get_order_book("COB-BTC", $limit);
if (!$depth["error"]) {
    print_r($depth);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [sequence] => 0 // A sequence number that is updated on each orderbook state change
    [bids] => Array
        (
            [0] => Array
                (
                    [price] => 0.000016
                    [orders] => 4
                    [quantity] => 8679.19589141
                )

            [1] => Array
                (
                    [price] => 0.00001598
                    [orders] => 1
                    [quantity] => 1205.14017522
                )

            [2] => Array
                (
                    [price] => 0.00001576
                    [orders] => 1
                    [quantity] => 400
                )

            [3] => Array
                (
                    [price] => 0.00001573
                    [orders] => 1
                    [quantity] => 3000
                )

            [4] => Array
                (
                    [price] => 0.00001571
                    [orders] => 2
                    [quantity] => 1546.35264163
                )

        )

    [asks] => Array
        (
            [0] => Array
                (
                    [price] => 0.0000163
                    [orders] => 1
                    [quantity] => 1231.65982242
                )

            [1] => Array
                (
                    [price] => 0.00001635
                    [orders] => 1
                    [quantity] => 1021.06924201
                )

            [2] => Array
                (
                    [price] => 0.00001638
                    [orders] => 1
                    [quantity] => 267.89464205
                )

            [3] => Array
                (
                    [price] => 0.00001639
                    [orders] => 1
                    [quantity] => 158.801
                )

            [4] => Array
                (
                    [price] => 0.0000166
                    [orders] => 1
                    [quantity] => 1389.66977033
                )

        )

)
```
</details>

#### Get all currencies
Returns info for all currencies available for trade
```php
$currencies = $cobinhood->get_currencies();
if (!$currencies["error"]) {
    print_r($currencies);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [currency] => ETH
            [name] => Ethereum
            [type] => native
            [min_unit] => 0.00000001
            [deposit_fee] => 0
            [withdrawal_fee] => 0.007
            [is_active] => 1
            [funding_frozen] => 
        )

    [1] => Array
        (
            [currency] => FSN
            [name] => Fusion Token
            [type] => erc20
            [min_unit] => 0.00000001
            [deposit_fee] => 0
            [withdrawal_fee] => 2.45
            [is_active] => 1
            [funding_frozen] => 
        )

    [2] => Array
        (
            [currency] => EOS
            [name] => EOS
            [type] => erc20
            [min_unit] => 0.00000001
            [deposit_fee] => 0
            [withdrawal_fee] => 0.98
            [is_active] => 1
            [funding_frozen] => 
        )

    [...]
)
```
</details>

#### Get info for all trading pairs
```php
$trading_pairs = $cobinhood->get_trading_pairs();
if (!$trading_pairs["error"]) {
    print_r($trading_pairs);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => MANA-ETH
            [base_currency_id] => MANA
            [quote_currency_id] => ETH
            [base_max_size] => 9501368.197
            [base_min_size] => 285.041
            [quote_increment] => 0.0000001
            [is_active] => 1
        )

    [1] => Array
        (
            [id] => SPHTX-USDT
            [base_currency_id] => SPHTX
            [quote_currency_id] => USDT
            [base_max_size] => 2177131.411
            [base_min_size] => 65.313
            [quote_increment] => 0.0001
            [is_active] => 1
        )

    [2] => Array
        (
            [id] => BDG-USDT
            [base_currency_id] => BDG
            [quote_currency_id] => USDT
            [base_max_size] => 16014630.966
            [base_min_size] => 480.438
            [quote_increment] => 0.00001
            [is_active] => 1
        )

    [...]
)
```
</details>

#### Get trading statistics
```php
$stats = $cobinhood->get_stats();
if (!$stats["error"]) {
    print_r($stats);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [ABT-BTC] => Array
        (
            [id] => ABT-BTC
            [last_price] => 0.000114
            [lowest_ask] => 0.00012989
            [highest_bid] => 0.00011214
            [base_volume] => 10078.34775034
            [quote_volume] => 1.185514799242868
            [is_frozen] => 
            [high_24hr] => 0.00013181
            [low_24hr] => 0.00011337
            [percent_changed_24hr] => -0.0338164251207729
        )

    [ABT-ETH] => Array
        (
            [id] => ABT-ETH
            [last_price] => 0.00145
            [lowest_ask] => 0.001493
            [highest_bid] => 0.00145
            [base_volume] => 11826.95202817
            [quote_volume] => 17.354522143076703
            [is_frozen] => 
            [high_24hr] => 0.0014948
            [low_24hr] => 0.0014001
            [percent_changed_24hr] => 0
        )

    [ABT-USDT] => Array
        (
            [id] => ABT-USDT
            [last_price] => 1.1002
            [lowest_ask] => 1.6991
            [highest_bid] => 1.23
            [base_volume] => 274.88842359
            [quote_volume] => 390.962943633718
            [is_frozen] => 
            [high_24hr] => 1.7899
            [low_24hr] => 1.1
            [percent_changed_24hr] => -0.385328789317839
        )

    [...]
)
```
</details>

#### Get ticker of a symbol
```php
$ticker = $cobinhood->get_ticker("COB-BTC");
if (!$ticker["error"]) {
    print_r($ticker);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [trading_pair_id] => COB-BTC
    [timestamp] => 1520075940000
    [24h_high] => 0.00001949
    [24h_low] => 0.00001571
    [24h_open] => 0.000018
    [24h_volume] => 385807.29911329993
    [last_trade_price] => 0.00001571
    [highest_bid] => 0.00001571
    [lowest_ask] => 0.00001627
)
```
</details>

#### Get tickers of all symbols
```php
$tickers = $cobinhood->get_tickers();
if (!$tickers["error"]) {
    print_r($tickers);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [trading_pair_id] => MANA-ETH
            [timestamp] => 1520114520000
            [24h_high] => 0.0001278
            [24h_low] => 0.0001106
            [24h_open] => 0.0001106
            [24h_volume] => 3224.93485295
            [last_trade_price] => 0.000125
            [highest_bid] => 0.0001133
            [lowest_ask] => 0.0001365
        )

    [1] => Array
        (
            [trading_pair_id] => SPHTX-USDT
            [timestamp] => 1520114520000
            [24h_high] => 0
            [24h_low] => 0
            [24h_open] => 0
            [24h_volume] => 0
            [last_trade_price] => 0
            [highest_bid] => 0.0578
            [lowest_ask] => 3.9956
        )

    [2] => Array
        (
            [trading_pair_id] => BDG-USDT
            [timestamp] => 1520114520000
            [24h_high] => 0.149
            [24h_low] => 0.149
            [24h_open] => 0.149
            [24h_volume] => 0
            [last_trade_price] => 0.149
            [highest_bid] => 0.01173
            [lowest_ask] => 0.14599
        )

    [...]

)
```
</details>

#### Get recent trades of a symbol
```php
$limit = 3; // Optional. Defaults to 20 if not specified, max 50.

$trades = $cobinhood->get_trades("COB-BTC", $limit);
if (!$trades["error"]) {
    print_r($trades);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 78234fdd-15f7-4601-9468-00f5be23c794
            [maker_side] => bid
            [timestamp] => 1520071784525
            [price] => 0.00001604
            [size] => 666.21698565
        )

    [1] => Array
        (
            [id] => 6db8992e-cfc5-410c-8114-e34bfbda1c58
            [maker_side] => bid
            [timestamp] => 1520071702015
            [price] => 0.00001605
            [size] => 1394.13
        )

    [2] => Array
        (
            [id] => ee5271d2-a991-4d87-bd5c-fb9f0457a2c2
            [maker_side] => bid
            [timestamp] => 1520071702009
            [price] => 0.00001605
            [size] => 155.311
        )

)
```
</details>

#### Get candles of a symbol
```php
$timeframe = "5m"; // Timeframes: 1m, 5m, 15m, 30m, 1h, 3h, 6h, 12h, 1D, 7D, 14D, 1M
$startTime = 1519307723000; // Optional. Unix timestamp in milliseconds. Defaults to 0 if not specified. You can set it to false.
$endTime   = 1519308723000; // Optional. Unix timestamp in milliseconds. Defaults to current server time if not specified. You can set it to false.

$candles = $cobinhood->get_candles("COB-BTC", $timeframe, $endTime, $startTime);
if (!$candles["error"]) {
    print_r($candles);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [timeframe] => 5m
            [trading_pair_id] => COB-BTC
            [timestamp] => 1519307700000
            [volume] => 0
            [open] => 0.0000162
            [close] => 0.0000162
            [high] => 0.0000162
            [low] => 0.0000162
        )

    [1] => Array
        (
            [timeframe] => 5m
            [trading_pair_id] => COB-BTC
            [timestamp] => 1519308000000
            [volume] => 0
            [open] => 0.0000162
            [close] => 0.0000162
            [high] => 0.0000162
            [low] => 0.0000162
        )

    [2] => Array
        (
            [timeframe] => 5m
            [trading_pair_id] => COB-BTC
            [timestamp] => 1519308300000
            [volume] => 814.81238504
            [open] => 0.0000163
            [close] => 0.00001631
            [high] => 0.00001631
            [low] => 0.0000163
        )

    [3] => Array
        (
            [timeframe] => 5m
            [trading_pair_id] => COB-BTC
            [timestamp] => 1519308600000
            [volume] => 0
            [open] => 0.00001631
            [close] => 0.00001631
            [high] => 0.00001631
            [low] => 0.00001631
        )

)
```
</details>

#### Get server time
Returns server Unix timestamp in milliseconds
```php
$server_time = $cobinhood->get_server_time();
if (!$server_time["error"]) {
    echo $server_time;
    // 1520076475432
}
```

#### Get server information
```php
$server_info = $cobinhood->get_server_info();
if (!$server_info["error"]) {
    print_r($server_info);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [phase] => production
    [revision] => cb4d16
)
```
</details>

## Trading API

#### Place a LIMIT BUY order
```php
$price = 0.000017;
$quantity = 1000;

$limit_buy_order = $cobinhood->limit_buy("COB-BTC", $price, $quantity);
if (!$limit_buy_order["error"]) {
    print_r($limit_buy_order);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
    [trading_pair] => COB-BTC
    [side] => bid
    [type] => limit
    [price] => 0.000017
    [size] => 1000
    [filled] => 0
    [state] => queued
    [timestamp] => 1520076956189
    [eq_price] => 0
    [completed_at] => 
)
```
</details>

#### Place a LIMIT SELL order
```php
$price = 0.000017;
$quantity = 1000;

$limit_sell_order = $cobinhood->limit_sell("COB-BTC", $price, $quantity);
if (!$limit_sell_order["error"]) {
    print_r($limit_sell_order);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
    [trading_pair] => COB-BTC
    [side] => ask
    [type] => limit
    [price] => 0.000017
    [size] => 1000
    [filled] => 0
    [state] => queued
    [timestamp] => 1520076956189
    [eq_price] => 0
    [completed_at] => 
)
```
</details>

#### Place a MARKET BUY order
```php
$quantity = 1000;

$market_buy_order = $cobinhood->market_buy("COB-BTC", $quantity);
if (!$market_buy_order["error"]) {
    print_r($market_buy_order);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
    [trading_pair] => COB-BTC
    [side] => bid
    [type] => market
    [price] => 0
    [size] => 1000
    [filled] => 0
    [state] => queued
    [timestamp] => 1520076956189
    [eq_price] => 0
    [completed_at] => 
)
```
</details>

#### Place a MARKET SELL order
```php
$quantity = 1000;

$market_sell_order = $cobinhood->market_sell("COB-BTC", $quantity);
if (!$market_sell_order["error"]) {
    print_r($market_sell_order);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
    [trading_pair] => COB-BTC
    [side] => ask
    [type] => market
    [price] => 0
    [size] => 1000
    [filled] => 0
    [state] => queued
    [timestamp] => 1520076956189
    [eq_price] => 0
    [completed_at] => 
)
```
</details>

#### Get an order's status
```php
$order_id = "37f550a2-2aa6-20f4-a3fe-e120f420637c";

$order_status = $cobinhood->get_order_status($order_id);
if (!$order_status["error"]) {
    print_r($order_status);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
    [trading_pair] => COB-BTC
    [side] => ask
    [type] => limit
    [price] => 0.000017
    [size] => 1000
    [filled] => 0
    [state] => open
    [timestamp] => 1520077138971
    [eq_price] => 0
    [completed_at] => 
)
```
</details>

#### Cancel an order
```php
$order_id = "37f550a2-2aa6-20f4-a3fe-e120f420637c";

$cancel_order = $cobinhood->cancel_order($order_id);
if (!$cancel_order["error"] && $cancel_order) {
    echo "Order cancelled";
    // Order cancelled
}
```

#### Modify an order
```php
$orderId = '37f550a2-2aa6-20f4-a3fe-e120f420637c';
$price = 0.000018;
$quantity = 1000;

$modify_order = $cobinhood->modify_order($order_id);
if (!$modify_order["error"] && $modify_order) {
    echo "Order modified";
    // Order modified
}
```

#### Get open orders of a symbol
```php
$limit = 2; // Optional. Defaults to 20 if not specified, max 50.

$open_orders = $cobinhood->get_open_orders("COB-ETH", $limit);
if (!$open_orders["error"]) {
    print_r($open_orders);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => ccb0c81c-08be-4df5-afe4-18039fd533ed
            [trading_pair] => COB-ETH
            [side] => bid
            [type] => limit
            [price] => 0.0001
            [size] => 200
            [filled] => 0
            [state] => open
            [timestamp] => 1520079812677
            [eq_price] => 0
            [completed_at] => 
        )

)
```
</details>

#### Get all open orders
```php
$limit = 2; // Optional. Defaults to 20 if not specified, max 50.

$open_orders_all = $cobinhood->get_open_orders_all($limit);
if (!$open_orders_all["error"]) {
    print_r($open_orders_all);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => ccb0c81c-08be-4df5-afe4-18039fd533ed
            [trading_pair] => COB-ETH
            [side] => bid
            [type] => limit
            [price] => 0.0001
            [size] => 200
            [filled] => 0
            [state] => open
            [timestamp] => 1520079812677
            [eq_price] => 0
            [completed_at] => 
        )

    [1] => Array
        (
            [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
            [trading_pair] => COB-BTC
            [side] => ask
            [type] => limit
            [price] => 0.000017
            [size] => 1000
            [filled] => 0
            [state] => open
            [timestamp] => 1520079812677
            [eq_price] => 0
            [completed_at] => 
        )

)
```
</details>

#### Get order's trades
```php
$order_id = "37f550a2-2aa6-20f4-a3fe-e120f420637c";

$order_trades = $cobinhood->get_order_trades($order_id);
if (!$order_trades["error"]) {
    print_r($order_trades);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 09619448e48a3bd73d493a4194f9020b
            [price] => 10.00000000
            [size] => 0.01000000
            [maker_side] => bid
            [timestamp] => 1504459805123
        )

    [1] => Array
        (
            [id] => 943829d8798d7d9s87984787d89799dd
            [price] => 10.00000000
            [size] => 0.05000000
            [maker_side] => bid
            [timestamp] => 1504459815765
        )

)
```
</details>

#### Get order history of a symbol
```php
$limit = 2; // Optional. Defaults to 50 if not specified.

$orders_history = $cobinhood->get_orders_history("COB-BTC", $limit);
if (!$orders_history["error"]) {
    print_r($orders_history);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
            [trading_pair] => COB-BTC
            [side] => ask
            [type] => limit
            [price] => 0.000017
            [size] => 1000
            [filled] => 0
            [state] => cancelled
            [timestamp] => 1520077138971
            [eq_price] => 0
            [completed_at] => 2018-03-03T11:49:43.126243Z
        )

    [1] => Array
        (
            [id] => e120f420-2aa6-20f4-a3fe-37f550a2637c
            [trading_pair] => COB-BTC
            [side] => bid
            [type] => limit
            [price] => 0.000017
            [size] => 1000
            [filled] => 1000
            [state] => filled
            [timestamp] => 1520076956189
            [eq_price] => 0.000017
            [completed_at] => 2018-03-03T11:38:54.571411Z
        )

)
```
</details>

#### Get all order history
```php
$limit = 2; // Optional. Defaults to 50 if not specified.

$orders_history_all = $cobinhood->get_orders_history_all($limit);
if (!$orders_history_all["error"]) {
    print_r($orders_history_all);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 37f550a2-2aa6-20f4-a3fe-e120f420637c
            [trading_pair] => COB-BTC
            [side] => ask
            [type] => limit
            [price] => 0.000017
            [size] => 1000
            [filled] => 0
            [state] => cancelled
            [timestamp] => 1520077138971
            [eq_price] => 0
            [completed_at] => 2018-03-03T11:49:43.126243Z
        )

    [1] => Array
        (
            [id] => e120f420-2aa6-20f4-a3fe-37f550a2637c
            [trading_pair] => COB-BTC
            [side] => bid
            [type] => limit
            [price] => 0.000017
            [size] => 1000
            [filled] => 1000
            [state] => filled
            [timestamp] => 1520076956189
            [eq_price] => 0.000017
            [completed_at] => 2018-03-03T11:38:54.571411Z
        )

)
```
</details>

## Wallet API

#### Get wallet balances
```php
$balances = $cobinhood->get_balances();
if (!$balances["error"]) {
    print_r($balances);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [currency] => BTC
            [type] => exchange
            [total] => 0
            [on_order] => 0
            [locked] => 
        )

    [1] => Array
        (
            [currency] => ETH
            [type] => exchange
            [total] => 0.09849225
            [on_order] => 0.04
            [locked] => 
        )

    [2] => Array
        (
            [currency] => COB
            [type] => exchange
            [total] => 2000
            [on_order] => 0
            [locked] => 
        )

)
```
</details>

#### Get balance history of a currency
```php
$limit = 3; // Defaults to 20 if not specified, max 50.

$balance_history = $cobinhood->get_balance_history("ETH", $limit);
if (!$balance_history["error"]) {
    print_r($balance_history);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [timestamp] => 2018-02-27T03:11:45.132432Z
            [currency] => ETH
            [type] => exchange
            [action] => withdraw
            [amount] => 0.000543
            [balance] => 0
            [trade_id] => 
            [deposit_id] => 
            [withdrawal_id] => 09619448-985d-4485-835e-961944824482
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

    [1] => Array
        (
            [timestamp] => 2018-02-26T23:14:08.435243Z
            [currency] => ETH
            [type] => exchange
            [action] => trade
            [amount] => 0.000543
            [balance] => 0.050543
            [trade_id] => b6909619-985d-4485-835e-b69096194482
            [deposit_id] => 
            [withdrawal_id] => 
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

    [2] => Array
        (
            [timestamp] => 2018-02-26T17:18:59.428914Z
            [currency] => ETH
            [type] => exchange
            [action] => deposit
            [amount] => 0.05
            [balance] => 0.05
            [trade_id] => 
            [deposit_id] => 96194482-985d-4485-835e-096194484482
            [withdrawal_id] => 
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

)
```
</details>

#### Get all balance history
```php
$limit = 3; // Defaults to 20 if not specified, max 50.

$balance_history_all = $cobinhood->get_balance_history_all($limit);
if (!$balance_history_all["error"]) {
    print_r($balance_history_all);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [timestamp] => 2018-02-27T03:11:45.132432Z
            [currency] => ETH
            [type] => exchange
            [action] => withdraw
            [amount] => 0.000543
            [balance] => 0
            [trade_id] => 
            [deposit_id] => 
            [withdrawal_id] => 09619448-985d-4485-835e-961944824482
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

    [1] => Array
        (
            [timestamp] => 2018-02-26T23:14:08.435243Z
            [currency] => COB
            [type] => exchange
            [action] => trade
            [amount] => 100
            [balance] => 2100
            [trade_id] => b6909619-985d-4485-835e-b69096194482
            [deposit_id] => 
            [withdrawal_id] => 
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

    [2] => Array
        (
            [timestamp] => 2018-02-26T17:18:59.428914Z
            [currency] => BTC
            [type] => exchange
            [action] => deposit
            [amount] => 0.05
            [balance] => 0.05
            [trade_id] => 
            [deposit_id] => 96194482-985d-4485-835e-096194484482
            [withdrawal_id] => 
            [fiat_deposit_id] => 
            [fiat_withdrawal_id] => 
        )

)
```
</details>

#### Get deposit addresses of a currency
```php
$deposit_addresses = $cobinhood->get_deposit_addresses("ETH");
if (!$deposit_addresses["error"]) {
    print_r($deposit_addresses);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [address] => 0xbcd7defe48a19f758a1c1a9706e808072391bc20
            [created_at] => 1519234408062
            [currency] => ETH
            [type] => exchange
        )

)
```
</details>

#### Get all deposit addresses
```php
$deposit_addresses_all = $cobinhood->get_deposit_addresses_all();
if (!$deposit_addresses_all["error"]) {
    print_r($deposit_addresses_all);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [address] => 0xbcd7defe48a19f758a1c1a9706e808072391bc20
            [created_at] => 1519234408062
            [currency] => ETH
            [type] => exchange
        )

    [1] => Array
        (
            [address] => 0xbff7defe48a09619448e48a3bd73a4194f9020b3
            [created_at] => 1519232304035
            [currency] => BTC
            [type] => exchange
        )

)
```
</details>

#### Get deposit's status
```php
$deposit_id = "09619448-985d-4485-835e-b69096194482";

$deposit_status = $cobinhood->get_deposit_status($deposit_id);
if (!$deposit_status["error"]) {
    print_r($deposit_status);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [amount] => 0.05
    [completed_at] => 1519319949428
    [confirmations] => 25
    [created_at] => 1519319554624
    [currency] => ETH
    [deposit_id] => 09619448-985d-4485-835e-b69096194482
    [fee] => 0
    [from_address] => 0xFBb1b73C4f0BDa4f67dcA266ce6Ef42f520fBB97
    [required_confirmations] => 25
    [status] => tx_confirmed
    [txhash] => 0x3f694510b9fca0ce752118be2525726473b541c86f5756de9ee693005d32bb23
    [user_id] => efb9f645-f457-413b-b187-93cab09d8727
)
```
</details>

#### Get all deposits
```php
$deposits = $cobinhood->get_deposits();
if (!$deposits["error"]) {
    print_r($deposits);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [amount] => 0.05
            [completed_at] => 1519319949428
            [confirmations] => 25
            [created_at] => 1519319554624
            [currency] => ETH
            [deposit_id] => 09619448-985d-4485-835e-b69096194482
            [fee] => 0
            [from_address] => 0xFBb1b73C4f0BDa4f67dcA266ce6Ef42f520fBB97
            [required_confirmations] => 25
            [status] => tx_confirmed
            [txhash] => 0x3f694510b9fca0ce752118be2525726473b541c86f5756de9ee693005d32bb23
            [user_id] => efb9f645-f457-413b-b187-93cab09d8727
        )

    [1] => Array
        (
            [amount] => 0.2
            [completed_at] => 15193100495243
            [confirmations] => 25
            [created_at] => 1519319554624
            [currency] => BTC
            [deposit_id] => 74f6376d-985d-4485-835e-b69096194482
            [fee] => 0
            [from_address] => 0xFBb1b73C4f0BDa4f67dcA266ce6Ef42f520fBB97
            [required_confirmations] => 25
            [status] => tx_confirmed
            [txhash] => 0x3f694510b9fca0ce752118be2525726473b541c86f5756de9ee693005d32bb23
            [user_id] => efb9f645-f457-413b-b187-93cab09d8727
        )

    [...]
)
```
</details>

#### Get withdrawal addresses of a currency
```php
$withdrawal_addresses = $cobinhood->get_withdrawal_addresses("ETH");
if (!$withdrawal_addresses["error"]) {
    print_r($withdrawal_addresses);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 6b01c8ac-3075-4ae5-a485-881d67Fe78fb
            [name] => Seychelles
            [type] => exchange
            [currency] => ETH
            [address] => 0xA6854dFD1BA0635f03a275ce9f3b310F52396673
            [created_at] => 1519723240162
        )
    
    [...]
)
```
</details>

#### Get all withdrawal addresses
```php
$withdrawal_addresses_all = $cobinhood->get_withdrawal_addresses_all();
if (!$withdrawal_addresses_all["error"]) {
    print_r($withdrawal_addresses_all);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [id] => 6b01c8ac-3075-4ae5-a485-881d67Fe78fb
            [name] => Seychelles
            [type] => exchange
            [currency] => ETH
            [address] => 0xA6854dFD1BA0635f03a275ce9f3b310F52396673
            [created_at] => 1519723240162
        )

    [1] => Array
        (
            [id] => 6b01c8ac-3075-4ae5-a485-881d67Fe78fb
            [name] => Wakanda
            [type] => exchange
            [currency] => BTC
            [address] => 0xA6854dFD1BA0635f03a275ce9f3b310F52g873hd
            [created_at] => 1519723240162
        )
    
    [...]
)
```
</details>

#### Get withdrawal's status
```php
$withdrawal_id = "09619448-985d-4485-835e-b69096194482";

$withdrawal_status = $cobinhood->get_withdrawal_status($withdrawal_id);
if (!$withdrawal_status["error"]) {
    print_r($withdrawal_status);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [amount] => 0.021
    [completed_at] => 
    [confirmations] => 25
    [created_at] => 1519319554624
    [currency] => BTC
    [withdrawal_id] => 09619448-985d-4485-835e-b69096194482
    [fee] => 0.0003
    [sent_at] => 1519319554624
    [required_confirmations] => 25
    [status] => pending
    [txhash] => 0x3f694510b9fca0ce752118be2525726473b541c86f5756de9ee693005d32bb23
    [user_id] => efb9f645-f457-413b-b187-93cab09d8727
)
```
</details>

#### Get all withdrawals
```php
$withdrawals = $cobinhood->get_withdrawals();
if (!$withdrawals["error"]) {
    print_r($withdrawals);
}
```

<details>
<summary>Response</summary>

```
Array
(
    [0] => Array
        (
            [amount] => 0.021
            [completed_at] => 
            [confirmations] => 25
            [created_at] => 1519319554624
            [currency] => BTC
            [withdrawal_id] => 09619448-985d-4485-835e-b69096194482
            [fee] => 0.0003
            [sent_at] => 1519319554624
            [required_confirmations] => 25
            [status] => pending
            [txhash] => 0x3f694510b9fca0ce752118be2525726473b541c86f5756de9ee693005d32bb23
            [user_id] => efb9f645-f457-413b-b187-93cab09d8727
        )

    [...]
)
```
</details>

## Todos
 - Websockets
 - Examples
