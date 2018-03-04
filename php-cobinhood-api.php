<?php
/* ============================================================
 * php-cobinhood-api
 * https://github.com/buzorin/php-cobinhood-api
 * ============================================================
 * Copyright 2018-, Konstantin Buzorin
 * Released under the MIT License
 * ============================================================ */

namespace Cobinhood;
class API_client {
	protected $base_url = "https://api.cobinhood.com";
	public function __construct($api_key) {
		$this->api_key = $api_key;
	}
	protected $default_curl_opt = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_USERAGENT      => "Mozilla/4.0 (compatible; PHP Cobinhood API)",
		CURLOPT_ENCODING       => "",
		CURLOPT_HEADER         => false,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_MAXREDIRS      => 5,
		CURLOPT_AUTOREFERER    => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_CONNECTTIMEOUT => 30,
		CURLOPT_TIMEOUT        => 30
	];

	private function pubRequest($path, $query = "") {
		if ($query)
			$query = "?".http_build_query($query);

		$ch = curl_init($this->base_url.$path.$query);
		curl_setopt_array($ch, $this->default_curl_opt);
		$content = curl_exec($ch);
		$errno   = curl_errno($ch);
		$errmsg  = curl_error($ch);
		$header  = curl_getinfo($ch);
		curl_close($ch);

		if ($errno !== 0)
			return ["error" => $errmsg];

		$content = json_decode($content, true);
		if (null === $content && json_last_error() !== JSON_ERROR_NONE) {
			return ["error" => "json_decode() error"];
		} else if (false === $content["success"]) {
			return ["error" => $content["error"]["error_code"]];
		}

		return $content;
	}

	private function authRequest($path, $method = "GET", $params = false, $query = "") {
		$opt = $this->default_curl_opt;
		
		if ("GET" !== $method)
			$opt[CURLOPT_CUSTOMREQUEST] = $method;

		if ($params)
			$opt[CURLOPT_POSTFIELDS] = json_encode($params);

		if ($query)
			$query = "?".http_build_query($query);

		$nonce = intval(round(microtime(true)*1000));
		$opt[CURLOPT_HTTPHEADER] = [
			"authorization: {$this->api_key}",
			"nonce: {$nonce}"
		];
		
		$ch = curl_init($this->base_url.$path.$query);
		curl_setopt_array($ch, $opt);
		$content = curl_exec($ch);
		$errno   = curl_errno($ch);
		$errmsg  = curl_error($ch);
		$header  = curl_getinfo($ch);
		curl_close($ch);

		if ($errno !== 0)
			return ["error" => $errmsg];

		$content = json_decode($content, true);
		if (null === $content && json_last_error() !== JSON_ERROR_NONE) {
			return ["error" => "json_decode() error"];
		} else if (false === $content["success"]) {
			return ["error" => $content["error"]["error_code"]];
		}

		return $content;
	}

	private function place_order($symbol, $price, $quantity, $side, $type) {
		$params = [
			"trading_pair_id" => $symbol,
			"side"            => $side,
			"type"            => $type,
			"price"           => strval($price),
			"size"            => strval($quantity)
		];
		$order = $this->authRequest("/v1/trading/orders", "POST", $params);
		return $order;
	}

	public function get_server_time() {
		$data = $this->pubRequest("/v1/system/time");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["time"];
	}
	public function get_server_info() {
		$data = $this->pubRequest("/v1/system/info");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["info"];
	}
	public function get_currencies() {
		$data = $this->pubRequest("/v1/market/currencies");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["currencies"];
	}
	public function get_trading_pairs() {
		$data = $this->pubRequest("/v1/market/trading_pairs");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["trading_pairs"];
	}
	public function get_order_book($symbol, $limit = 50) {
		$query = ["limit" => $limit];
		$data = $this->pubRequest("/v1/market/orderbooks/{$symbol}", $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		$orderbook = [
			"sequence" => $data["result"]["orderbook"]["sequence"],
			"bids"     => [],
			"asks"     => []
		];
		foreach ($data["result"]["orderbook"]["bids"] as $bid) {
			array_push($orderbook["bids"], [
				"price"    => $bid[0],
				"orders"   => $bid[1],
				"quantity" => $bid[2]
			]);
		}
		foreach ($data["result"]["orderbook"]["asks"] as $ask) {
			array_push($orderbook["asks"], [
				"price"    => $ask[0],
				"orders"   => $ask[1],
				"quantity" => $ask[2]
			]);
		}
		return $orderbook;
	}
	public function get_stats() {
		$data = $this->pubRequest("/v1/market/stats");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"];
	}
	public function get_ticker($symbol) {
		$data = $this->pubRequest("/v1/market/tickers/{$symbol}");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["ticker"];
	}
	public function get_tickers() {
		$data = $this->pubRequest("/v1/market/tickers");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["tickers"];
	}
	public function get_last_price($symbol) {
		$data = $this->pubRequest("/v1/market/tickers/{$symbol}");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["ticker"]["last_trade_price"];
	}
	public function get_trades($symbol, $limit = 20) {
		$query = ["limit" => $limit];
		$data = $this->pubRequest("/v1/market/trades/{$symbol}", $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["trades"];
	}
	public function get_candles($symbol, $timeframe, $endTime = false, $startTime = false) {
		$query = ["timeframe" => $timeframe];
		if ($endTime) $query["end_time"] = $endTime;
		if ($startTime) $query["start_time"] = $startTime;
		$data = $this->pubRequest("/v1/chart/candles/{$symbol}", $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["candles"];
	}
	public function limit_buy($symbol, $price, $quantity) {
		$data = $this->place_order($symbol, $price, $quantity, 'bid', 'limit');
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["order"];
	}
	public function limit_sell($symbol, $price, $quantity) {
		$data = $this->place_order($symbol, $price, $quantity, 'ask', 'limit');
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["order"];
	}
	public function market_buy($symbol, $quantity) {
		$data = $this->place_order($symbol, "", $quantity, 'bid', 'market');
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["order"];
	}
	public function market_sell($symbol, $quantity) {
		$data = $this->place_order($symbol, "", $quantity, 'ask', 'market');
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["order"];
	}
	public function get_order_status($order_id) {
		$data = $this->authRequest("/v1/trading/orders/{$order_id}", "GET");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["order"];
	}
	public function modify_order($order_id, $price, $quantity) {
		$params = ["price" => strval($price), "size" => strval($quantity)];
		$data = $this->authRequest("/v1/trading/orders/{$order_id}", "PUT", $params);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["success"];
	}
	public function cancel_order($order_id) {
		$data = $this->authRequest("/v1/trading/orders/{$order_id}", "DELETE");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["success"];
	}
	public function get_open_orders($symbol, $limit = 20) {
		$query = ["trading_pair_id" => $symbol, "limit" => $limit];
		$data = $this->authRequest("/v1/trading/orders", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["orders"];
	}
	public function get_open_orders_all($limit = 20) {
		$query = ["limit" => $limit];
		$data = $this->authRequest("/v1/trading/orders", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["orders"];
	}
	public function get_order_trades($order_id) {
		$data = $this->authRequest("/v1/trading/orders/{$order_id}/trades");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["trades"];
	}
	public function get_orders_history($symbol, $limit = 50) {
		$query = ["trading_pair_id" => $symbol, "limit" => $limit];
		$data = $this->authRequest("/v1/trading/order_history", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["orders"];
	}
	public function get_orders_history_all($limit = 50) {
		$query = ["limit" => $limit];
		$data = $this->authRequest("/v1/trading/order_history", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["orders"];
	}
	public function get_balances() {
		$data = $this->authRequest("/v1/wallet/balances");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["balances"];
	}
	public function get_balance_history($currency, $limit = 20) {
		$query = ["currency" => $currency, "limit" => $limit];
		$data = $this->authRequest("/v1/wallet/ledger", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["ledger"];
	}
	public function get_balance_history_all($limit = 20) {
		$query = ["limit" => $limit];
		$data = $this->authRequest("/v1/wallet/ledger", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["ledger"];
	}
	public function get_deposit_addresses($currency) {
		$query = ["currency" => $currency];
		$data = $this->authRequest("/v1/wallet/deposit_addresses", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["deposit_addresses"];
	}
	public function get_deposit_addresses_all() {
		$data = $this->authRequest("/v1/wallet/deposit_addresses");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["deposit_addresses"];
	}
	public function get_deposit_status($deposit_id) {
		$data = $this->authRequest("/v1/wallet/deposits/{$deposit_id}");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["deposit"];
	}
	public function get_deposits() {
		$data = $this->authRequest("/v1/wallet/deposits");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["deposits"];
	}
	public function get_withdrawal_addresses($currency) {
		$query = ["currency" => $currency];
		$data = $this->authRequest("/v1/wallet/withdrawal_addresses", "GET", false, $query);
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["withdrawal_addresses"];
	}
	public function get_withdrawal_addresses_all() {
		$data = $this->authRequest("/v1/wallet/withdrawal_addresses");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["withdrawal_addresses"];
	}
	public function get_withdrawal_status($withdrawal_id) {
		$data = $this->authRequest("/v1/wallet/withdrawals/{$withdrawal_id}");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["withdrawal"];
	}
	public function get_withdrawals() {
		$data = $this->authRequest("/v1/wallet/withdrawals");
		if ($data["error"])
			return $data; // Handle error if method has ["error"] key in return
		return $data["result"]["withdrawals"];
	}
}










