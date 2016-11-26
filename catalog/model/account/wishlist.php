<?php
class ModelAccountWishlist extends Model {
	public function addWishlist($instrument_id) {
		$this->event->trigger('pre.wishlist.add');

		$this->db->query("DELETE FROM " . DB_PREFIX . "user_wishlist WHERE user_id = '" . (int)$this->user->getId() . "' AND instrument_id = '" . (int)$instrument_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "user_wishlist SET user_id = '" . (int)$this->user->getId() . "', instrument_id = '" . (int)$instrument_id . "', date_added = NOW()");

		$this->event->trigger('post.wishlist.add');
	}

	public function deleteWishlist($instrument_id) {
		$this->event->trigger('pre.wishlist.delete');

		$this->db->query("DELETE FROM " . DB_PREFIX . "user_wishlist WHERE user_id = '" . (int)$this->user->getId() . "' AND instrument_id = '" . (int)$instrument_id . "'");

		$this->event->trigger('post.wishlist.delete');
	}

	public function getWishlist() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_wishlist WHERE user_id = '" . (int)$this->user->getId() . "'");

		return $query->rows;
	}

	public function getTotalWishlist() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_wishlist WHERE user_id = '" . (int)$this->user->getId() . "'");

		return $query->row['total'];
	}
}
