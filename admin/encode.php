<?php
if(!empty($argv[1]) && (2 == $argc)) {
    echo '{SHA}'.base64_encode(sha1($argv[1], true));
} else {
    throw new RuntimeException('Wrong argument supplied for encode');
}
exit;
