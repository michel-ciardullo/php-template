<?php

namespace App;

class BaseView {

    /**
     * @var string
     */
    private $resource;

    /**
     * @var string
     */
    private $storage;

    /**
     * @var array
     */
    private $str_replace = [
        [
            '{{', '}}',
            '{!!', '!!}',
            '@else', '@endsection', '@endforeach', '@endif', '@endfor', '@while',
            '@php', '@endphp',
            '@endswitch',
            '&lt;?=', '?&gt;',
            '@endpush'
        ],
        [
            '<?= htmlentities(', ') ?>',
            '<?=', '?>',
            '<?php else: ?>', '<?php }); ?>', '<?php endforeach; ?>', '<?php endif; ?>', '<?php endfor; ?>', '<?php endwhile; ?>',
            '<?php', '?>',
            '<?php endswitch; ?>',
            '<?=', '?>',
            '<?php }); ?>'
        ]
    ];

    /**
     * @var array
     */
    private $preg_replace = [
        [
            '~@inject\((.*?), ([^()]*+(?:\((?-1)\)[^()]*)*+)\)~',
            '~@extend\((.*?)\)~',
            '~@section\((.*?), (.*?)\)~',
            '~@section\((.*?)\)~',
            "~@yield\((.*?)\)~",
            '~@(if|elseif|for|foreach|while|switch)\(([^()]*+(?:\((?-1)\)[^()]*)*+)\)~',
            '~@include\((.*?)\)~',
            '~@json\((.*?)\)~',
            '~@stack\((.*?)\)~',
            '~@push\((.*?)\)~',
        ],
        [
            '<?= $this->inject($1, $2) ?>',
            '<?php $_layout = $1; ?>',
            '<?php $this->section($1, $2); ?>',
            '<?php $this->section($1, function($args) { extract($args); ?>',
            '<?= $this->yield($1, $args); ?>',
            '<?php $1($2): ?>',
            '<?= $this->render($1) ?>',
            '<?= json_encode($1); ?>',
            '<?= $this->stack($1, $args); ?>',
            '<?php $this->push($1, function($args) { extract($args); ?>',
        ]
    ];

    /**
     * @var array
     */
    private $sections = [];

    /**
     * @var array
     */
    private $globals = [];

    /**
     * @var array
     */
    private $functions = [];

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var null
     */
    private $content = null;

    /**
     * @var array
     */
    private $components = [];

    /**
     * @var array
     */
    private $stacks = [];

    /**
     * BaseView constructor.
     * @param string $resource
     * @param string $storage
     */
    public function __construct(string $resource, string $storage)
    {
        $this->resource = $resource;
        $this->storage = $storage;
    }

    /**
     * @param string $path
     * @param array $args
     * @return string
     */
    public function render(string $path, array $args = []): string
    {
        foreach ($args as $i => $v)
        {
            $this->addGlobal($i, $v);
        }

        # get le content
        $resource = str_replace('.', '/', $this->resource . '/' . $path) . '.view';
        $this->content = file_get_contents($resource);

        # replace les tag de base
        $this->content = preg_replace($this->preg_replace[0], $this->preg_replace[1], $this->content);
        $this->content = str_replace($this->str_replace[0], $this->str_replace[1], $this->content);

        # get et add tag filter
        foreach ($this->filters as $key => $value)
        {
            $this->content = preg_replace_callback(
                "~@filter\(\'(.*?)\', (.*?)\)~",
                function ($matches) use($key, $value) {
                    if ($key !== $matches[1]) {
                        return null;
                    }
                    return call_user_func_array($value, [$matches[2]]);
                },
                $this->content
            );
        }

        # get et add tag function
        foreach ($this->functions as $key => $value)
        {
            $this->content = preg_replace(
                "~@({$key})\(([^()]*+(?:\((?-1)\)[^()]*)*+)\)~",
                '<?= $$1($2); ?>',
                $this->content
            );
        }

        # get et add tag component
        foreach ($this->components as $key => $value)
        {
            $this->content = preg_replace_callback(
                "~@component\(\'(.*?)\', (.*?)\)~",
                function ($matches) use($key, $value) {
                    if ($key !== $matches[1]) {
                        return $matches[0];
                    }
                    return call_user_func_array($value, [$matches[2]]);
                },
                $this->content
            );
        }

        # get et add tag component file
        $this->content = preg_replace_callback(
            "~@component\(\'(.*?)\', (.*?)\)~",
            function ($matches) {
                return '<?= $this->render(\''.$matches[1].'\', '.$matches[2].') ?>';
            },
            $this->content
        );

        # create file in storage
        $storage = $this->storage . '/' . md5($path) . '.view.php';
        file_put_contents($storage, $this->content);

        # render le content put
        ob_start();
        $_layout = null;
        extract($this->sections + $this->globals + $this->functions + $this->filters + $args);

        include($storage);
        $_content = ob_get_clean();
        if (!is_null($_layout))
        {
            return $this->render($_layout, $args);
        }

        return $_content;
    }

    /**
     * @param string $name
     * @param $section
     */
    public function section(string $name, $section)
    {
        $this->sections[$name] = $section;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function yield(string $name, array $args = [])
    {
        if (array_key_exists($name, $this->sections)) {
            $variables = $this->sections + $this->globals + $this->functions + $this->filters + $args;
            $section = $this->sections[$name];
            if (is_string($section)) {
                return $section;
            }
            return call_user_func($section, $variables);
        }

        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addGlobal(string $key, $value)
    {
        $this->globals[$key] = $value;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addFunction(string $key, $value)
    {
        $this->functions[$key] = $value;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addFilter(string $key, $value)
    {
        $this->filters[$key] = $value;
    }

    /**
     * @param ViewExtension $extension
     */
    public function addExtension(ViewExtension $extension)
    {
        foreach ($extension->getGlobals() as $key => $value)
        {
            $this->addGlobal($key, $value);
        }

        foreach ($extension->getFunctions() as $key => $value)
        {
            $this->addFunction($key, $value);
        }

        foreach ($extension->getFilters() as $key => $value)
        {
            $this->addFilter($key, $value);
        }

        foreach ($extension->getComponents() as $key => $value)
        {
            $this->addComponent($key, $value);
        }
    }

    /**
     * @param $filter
     * @param $field
     * @return mixed
     */
    public function filter($filter, $field)
    {
        return call_user_func_array($filter, [$field]);
    }

    /**
     * @param string $key
     * @param string $class
     */
    public function inject(string $key, string $class)
    {
        $this->addGlobal($key, new $class);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function addComponent(string $key, $value)
    {
        $this->components[$key] = $value;
    }

    /**
     * @param string $name
     * @param $args
     * @return null|string
     */
    public function stack(string $name, $args)
    {
        if (array_key_exists($name, $this->stacks))
        {
            $push = null;
            foreach ($this->stacks[$name] as $stacks)
            {
                $variables = $this->stacks[$name] + $this->globals + $this->functions + $this->filters + $args;
                $push .= call_user_func($stacks, $variables);
            }
            return $push;
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function push(string $name, $value)
    {
        $this->stacks[$name][] = $value;
    }

}
