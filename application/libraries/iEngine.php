<?php

interface iEngine {

    public function search($em, $keywords, $exclude_words, $networks);
    public function remaining_api_calls();

}
