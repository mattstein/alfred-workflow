<?php

namespace Alfred\Workflows;

use Alfred\Workflows\ItemParam\Icon;
use Alfred\Workflows\ItemParam\Mod;
use Alfred\Workflows\ItemParam\Text;
use Alfred\Workflows\ItemParam\Type;

class Item
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * If this item is valid or not. If an item is valid then Alfred will action this
     * item when the user presses return. If the item is not valid, Alfred will do
     * nothing.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function valid(bool $valid = true): Item
    {
        $this->params['valid'] = !!$valid;

        return $this;
    }

    /**
     * By specifying "type": "file", this makes Alfred treat your result as a file
     * on your system. This allows the user to perform actions on the file like
     * they can with Alfred's standard file filters.
     *
     * When returning files, Alfred will check if the file exists before presenting
     * that result to the user. This has a very small performance implication but
     * makes the results as predictable as possible. If you would like Alfred to
     * skip this check as you are certain that the files you are returning exist,
     * you can use "type": "file:skipcheck".
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param \Alfred\Workflows\Item\Type::TYPE_* $type
     * @param bool $verify_existence When used with $type \Alfred\Workflows\Item::TYPE_FILE
     */
    public function type($type, bool $verify_existence = true): Item
    {
        $this->params['type'] = Type::handle($type, $verify_existence);

        return $this;
    }

    /**
     * The icon displayed in the result row. Workflows are run from their
     * workflow folder, so you can reference icons stored in your
     * workflow relatively.
     *
     * By omitting the "type", Alfred will load the file path itself, for
     * example a png. By using "type": "fileicon", Alfred will get the icon
     * for the specified path. Finally, by using "type": "filetype", you can
     * get the icon of a specific file, for example "path": "public.png"
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string $path
     * @param \Alfred\Workflows\Item\Icon::TYPE_* $type
     */
    public function icon(string $path, $type = null): Item
    {
        $this->params['icon'] = Icon::handle($path, $type);

        return $this;
    }

    /**
     * Alfred will get the icon for the specified file path. For example:
     *
     * my-image.png -> png icon
     * Alfred.app -> Alfred Icon
     * important-doc.pdf -> pdf icon
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function iconFromFile(string $path): Item
    {
        return $this->icon($path, Icon::TYPE_FILEICON);
    }

    /**
     * Get the icon of a specific file type, for example:
     * 'public.folder', 'jpg', 'png', 'pdf', 'sketch', etc.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function iconFromFileType(string $type): Item
    {
        return $this->icon($type, Icon::TYPE_FILETYPE);
    }

    /**
     * The subtitle displayed in the result row.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function subtitle(string $subtitle): Item
    {
        $this->params['subtitle'] = $subtitle;

        return $this;
    }

    /**
     * The text element defines the text the user will get when
     * copying the selected result row with ⌘C or displaying large type with ⌘L.
     *
     * If these are not defined, you will inherit Alfred's standard behaviour
     * where the arg is copied to the Clipboard or used for Large Type.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param \Alfred\Workflows\Item\Text::TYPE_* $type
     * @param string $text
     */
    public function text($type, string $text): Item
    {
        $this->mergeParam('text', Text::handle($type, $text));

        return $this;
    }

    /**
     * Defines the text the user will get when copying the
     * selected result row with ⌘C.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function copy(string $copy): Item
    {
        return $this->text(Text::TYPE_COPY, $copy);
    }

    /**
     * Defines the text the user will get when displaying
     * large type with ⌘L.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function largetype(string $largetype): Item
    {
        return $this->text(Text::TYPE_LARGETYPE, $largetype);
    }

    /**
     * The mod element gives you control over how the modifier keys react.
     * You can now define the valid attribute to mark if the result is valid based
     * on the modifier selection and set a different arg to be passed out
     * if actioned with the modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param \Alfred\Workflows\Item::MOD_* $mod
     * @param string $subtitle
     * @param string $arg
     * @param bool $valid
     *
     * TODO: Add variable option to this, also this is a ton of arguments, can we make easier?
     */
    public function mod($mod, string $subtitle, string $arg, bool $valid = true): Item
    {
        $this->mergeParam('mods', Mod::handle($mod, $subtitle, $arg, $valid));

        return $this;
    }

    /**
     * Define validity, arg, and variable using the cmd modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function cmd(string $subtitle, string $arg, bool $valid = true): Item
    {
        return $this->mod(Mod::MOD_CMD, $subtitle, $arg, $valid);
    }

    /**
     * Define validity, arg, and variable using the shift modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function shift(string $subtitle, string $arg, bool $valid = true): Item
    {
        return $this->mod(Mod::MOD_SHIFT, $subtitle, $arg, $valid);
    }

    /**
     * Define validity, arg, and variable using the fn modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function fn(string $subtitle, string $arg, bool $valid = true): Item
    {
        return $this->mod(Mod::MOD_FN, $subtitle, $arg, $valid);
    }

    /**
     * Define validity, arg, and variable using the ctrl modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function ctrl(string $subtitle, string $arg, bool $valid = true): Item
    {
        return $this->mod(Mod::MOD_CTRL, $subtitle, $arg, $valid);
    }

    /**
     * Define validity, arg, and variable using the alt modifier.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function alt(string $subtitle, string $arg, bool $valid = true): Item
    {
        return $this->mod(Mod::MOD_ALT, $subtitle, $arg, $valid);
    }

    /**
     * The match field enables you to define what Alfred matches against when
     * the workflow is set to "Alfred Filters Results". If match is present,
     * it fully replaces matching on the title property.
     *
     * Note that the match field is always treated as case insensitive, and
     * intelligently treated as diacritic insensitive. If the search query
     * contains a diacritic, the match becomes diacritic sensitive.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function match(string $match): Item
    {
        $this->params['match'] = $match;

        return $this;
    }

    /**
     * This is a unique identifier for the item which allows help
     * Alfred to learn about this item for subsequent sorting and
     * ordering of the user's actioned results.
     *
     * It is important that you use the same UID throughout subsequent
     * executions of your script to take advantage of Alfred's knowledge
     * and sorting. If you would like Alfred to always show the results
     * in the order you return them from your script, exclude the UID field.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function uid(string $uid): Item
    {
        $this->params['uid'] = $uid;

        return $this;
    }

    /**
     * The title displayed in the result row. There are no options
     * for this element and it is essential that this element is populated.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function title(string $title): Item
    {
        $this->params['title'] = $title;

        return $this;
    }

    /**
     * A Quick Look URL which will be visible if the user uses the Quick Look
     * feature within Alfred (tapping shift, or ⌘Y). Note that quicklookurl will
     * also accept a file path, both absolute and relative to home using ~/.
     *
     * If absent, Alfred will attempt to use the arg as the quicklook URL.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function quicklookurl(string $url): Item
    {
        $this->params['quicklookurl'] = $url;

        return $this;
    }

    /**
     * The argument which is passed through the workflow
     * to the connected output action.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     *
     * @param string|array $arg
     */
    public function arg($arg): Item
    {
        // TODO: IS THIS A CLASS? INVESTIGATE
        $this->params['arg'] = $arg;

        return $this;
    }

    /**
     * An optional but recommended string you can provide which is
     * populated into Alfred's search field if the user auto-complete's
     * the selected result (⇥ by default).
     *
     * If the item is set as "valid": false, the auto-complete text is
     * populated into Alfred's search field when the user actions the result.
     *
     * @link https://www.alfredapp.com/help/workflows/inputs/script-filter/json/
     */
    public function autocomplete(string $autocomplete): Item
    {
        $this->params['autocomplete'] = $autocomplete;

        return $this;
    }

    /**
     * Merge a param if it exists, create a new key if it doesn't
     */
    protected function mergeParam(string $key, array $value): void
    {
        if (array_key_exists($key, $this->params)) {
            $this->params[$key] = array_merge($this->params[$key], $value);
        } else {
            $this->params[$key] = $value;
        }
    }

    /**
     * Converts the results to an array structured for Alfred
     */
    public function toArray(): array
    {
        ksort($this->params);

        return $this->params;
    }

    public function __get(string $property)
    {
        if (array_key_exists($property, $this->params)) {
            return $this->params[$property];
        }

        return null;
    }
}
