CREATE OR REPLACE FUNCTION delete_expired_tokens() RETURNS void AS $$
BEGIN
    DELETE FROM tokens WHERE expiration <= NOW();
END;
$$ LANGUAGE plpgsql;