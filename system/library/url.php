<?php
class Url {
	private $domain;
	private $catalog_domain;
	private $ssl;
	private $catalog_ssl;
	private $rewrite = array();

	public function __construct($domain, $ssl = '', $catalog_domain = '', $catalog_ssl = '') {
		$this->domain = $domain;
		$this->ssl = $ssl;
		
		$this->catalog_domain = $catalog_domain;
		$this->catalog_ssl = $catalog_ssl;
	}

	public function addRewrite($rewrite) {
		$this->rewrite[] = $rewrite;
	}

	public function link($route, $args = '', $secure = false) {
		if (!$secure) {
			$url = $this->domain;
		} else {
			$url = $this->ssl;
		}

		$url .= 'index.php?route=' . $route;

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
	
	function catalogLink($route, $args = '', $secure = false){
		if (!$secure) {
			$url = $this->catalog_domain;
		} else {
			$url = $this->catalog_ssl;
		}

		$url .= 'index.php?route=' . $route;

		if ($args) {
			if (is_array($args)) {
				$url .= '&amp;' . http_build_query($args);
			} else {
				$url .= str_replace('&', '&amp;', '&' . ltrim($args, '&'));
			}
		}

		foreach ($this->rewrite as $rewrite) {
			$url = $rewrite->rewrite($url);
		}

		return $url;
	}
}
