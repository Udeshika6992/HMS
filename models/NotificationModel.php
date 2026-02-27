<?php
/**
 * Notification Model
 * Handles all notifications
 * Location: /models/NotificationModel.php
 */

class NotificationModel extends Model {
    
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'title', 'message', 'type',
        'is_read', 'link', 'sent_at', 'read_at'
    ];

    /**
     * Get unread notifications for user
     */
    public function getUnread($userId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id AND is_read = 0
                ORDER BY sent_at DESC";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Get all notifications for user
     */
    public function getForUser($userId, $limit = 20) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id
                ORDER BY sent_at DESC
                LIMIT " . (int)$limit;
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Mark as read
     */
    public function markAsRead($id) {
        return $this->update($id, [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all as read for user
     */
    public function markAllAsRead($userId) {
        $sql = "UPDATE {$this->table} 
                SET is_read = 1, read_at = NOW() 
                WHERE user_id = :user_id AND is_read = 0";
        return $this->db->query($sql, ['user_id' => $userId]);
    }

    /**
     * Send notification
     */
    public function send($userId, $title, $message, $type = 'system', $link = null) {
        return $this->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
            'sent_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get notification count
     */
    public function getUnreadCount($userId) {
        return $this->db->count(
            $this->table,
            'user_id = :user_id AND is_read = 0',
            ['user_id' => $userId]
        );
    }
}