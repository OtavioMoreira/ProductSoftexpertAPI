SELECT cron.schedule('0 * * * *', 'CALL delete_expired_tokens()');