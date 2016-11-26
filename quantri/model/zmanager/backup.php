<?php

class ModelZOrderrBackup extends Model {
    public function getBackup($id) {
        $id = (int)$id;
        $q = $this->db->query("SELECT * FROM " . DB_PREFIX . "files_backup WHERE id = '" . $id . "'");
        return $q->row;
    }

    public function getBackups($filepath) {
        $q = $this->db->query("SELECT `date`, `filepath`, `id` FROM " . DB_PREFIX . "files_backup WHERE `filepath` = '" . $this->db->escape($filepath) . "' ORDER BY `date` DESC");
        return $q->rows;
    }

    public function addBackup($filepath, $filecontent, $filetype) {
        $filepath = $this->db->escape($filepath);
        $filecontent = $this->db->escape($filecontent);
        $filetype = $this->db->escape($filetype);

        $q = $this->db->query("INSERT INTO " . DB_PREFIX . "files_backup SET type = '" . $filetype . "', `filepath` = '" . $filepath . "', `filecontent` = '" . $filecontent . "', `date` = '" . time() . "'");
        return $this->db->getLastId();
    }

    public function removeBackupsByfilepath($filepath, $time = 0) {
        $filepath = $this->db->escape($filepath);
        $time = (int)$time;
        $sql = "DELETE FROM " . DB_PREFIX . "files_backup WHERE `filepath` = '" . $filepath . "'";
        if ($time) {
            $sql .= " AND `date` <= $time ";
        }

        $this->db->query($sql);
    }

    public function removeAllBackups($except_latest_number = 0) {
        if($except_latest_number == 0) {
            $this->db->query("DELETE FROM ". DB_PREFIX . "files_backup WHERE 1");
        }
        else {
            $except_latest_number = (int) $except_latest_number - 1;

            $q = $this->db->query($sql = "SELECT DISTINCT(`filepath`) FROM ". DB_PREFIX . "files_backup WHERE 1");
            foreach($q->rows as $row) {
                $this->db->query("DELETE FROM ". DB_PREFIX . "files_backup
                  WHERE id <= (
                    SELECT id
                    FROM (
                      SELECT id
                      FROM ". DB_PREFIX . "files_backup
                      WHERE `filepath` = '". $this->db->escape($row['filepath']) . "'
                      ORDER BY id DESC
                      LIMIT 1 OFFSET ". $except_latest_number ."
                    ) foo
                  ) AND `filepath` = '". $this->db->escape($row['filepath']) . "'");
            }
        }
    }

    public function removeBackupById($id) {
        $id = (int)$id;
        $sql = "DELETE FROM " . DB_PREFIX . "files_backup WHERE id = " . $id;
        $this->db->query($sql);
    }
}
