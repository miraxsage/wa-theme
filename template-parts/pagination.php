<?php
    if(empty($args) || preg_match("/^(posts|comments)$/", $args) != 1)
        $args = "posts";
    $initial_links = ($args == "posts" ? paginate_links() : paginate_comments_links(['echo' => false]));
    if($initial_links) {
        $links = [];
        if (preg_match_all("/<(span|a).+<\/\\1/ui", $initial_links, $matches, PREG_SET_ORDER) >= 1) {
            foreach ($matches as $tag_match) {
                if (preg_match("/<a class=[\"'](prev |next )?page-numbers.*?href=[\"']([^\"']+)[\"'].*?>(.*?)</ui", $tag_match[0], $tag_matches) === 1)
                    $links[] = (object)["type" => empty($tag_matches[1]) ? "link" : trim($tag_matches[1]), "url" => $tag_matches[2], "text" => $tag_matches[3]];
                if (preg_match("/<span .*?class=[\"']page-numbers (current|dots).*?>(.*?)</ui", $tag_match[0], $tag_matches) === 1)
                    $links[] = (object)["type" => $tag_matches[1], "text" => $tag_matches[2]];
            }
        }
        $pagination_tag = WaThemeSettings::get()->common__pagination_use_nav ? "nav" : "div";
        echo '<'.$pagination_tag.' class="wa-pagination"><div class="wa-num-links">';
        foreach ($links as $link) {
            if ($link->type == "prev" || $link->type == "next")
                echo "<a class=\"wa-$link->type wa-page-numbers\" href=\"$link->url\"></a>";
            if ($link->type == "link")
                echo "<a class=\"wa-page-numbers\" href=\"$link->url\">$link->text</a>";
            if ($link->type == "current" || $link->type == "dots")
                echo "<span class=\"wa-page-numbers" . ($link->type == "current" ? " wa-current-page" : "") . "\">$link->text</span>";
        }
        echo '</div></'.$pagination_tag.'>';
    }