<?php

namespace Granola\Components\StyleGuide;

function testableComponents(): array
{
    // Get all the components
    $components = glob(\Granola\Asset::path('components/*'));

    // Get the theme path as the glob will include it and it needs to be removed
    $themePath = trailingslashit(\Granola\Paths::themePath());

    // Convert the path to theme relative paths rather than root
    $components = array_map(function ($component) use ($themePath) {
        $path = str_replace($themePath, '', $component);
        $id = str_replace('assets/components/', '', $path);

        $json = componentJSON($path);

        // If there is no declaration of whether or not we can render this
        // component
        if (empty($json->render)) {
            return false;
        }

        $args = resolveArgs($json->args);
        $variations = generateVariations($json, $args);

        return [
            'id' => $id,
            'path' => $path,
            'json' => $json,
            'args' => $args,
            'variations' => $variations,
        ];
    }, $components);

    // Filter out the components that do not have a block.json file as we cant
    // know how to render them.
    return array_filter($components);
}

// A recursive function to take block arg types and convert them to data
function resolveArgs($args)
{
    // Get the arguments that can be resolved
    $args = resolveableArgs($args);

    // This is a chunky bit of code, but in short it looks at the argument that
    // is needing to be rendered, and gets the data required for it. There is a
    // convention here that helps determine the course of behavior. Reason being
    // is that the possibilities for content types and data types are endless
    // and this doesn't work from a coding standpoint. So without further ado...

    // We determine how we are rendering by looking at the type that has been
    // defined for an argument. The following rules apply:
    // - String
    // The argument only supports one type of data
    // - Array
    // The argument supports multiple data types.
    // There is probably a todo here to extend the array to support objects. See below
    // - Object
    // The argument supports one data type, but there can be multiples of the
    // type. For example: a card in a cards component
    return array_map(function ($type) {
        if (is_string($type)) {
            // If its a string then we only need one component rendered
            return resolveStringTypeData($type);
        } elseif (is_array($type)) {
            // An array in the type represents variations. For the sake of
            // rendering the component, we use the first index as the default.

            // When we have an array of types, the types can either be a
            // primitive (text, wysiwyg, number) or it can be an object, which
            // represents a repeatable element. Therefore we need to check
            // before rendering the data.

            // Are we looking at a repeatable element
            if (is_object($type[0])) {
                return resolveObjectTypeData($type[0], true);
            } else {
                return resolveStringTypeData($type[0]);
            }
        } elseif (is_object($type)) {
            // An object in the type represents a repeatable element as we need
            // to provide a component and a count
            return resolveObjectTypeData($type);
        }

        // If we make it this far, its because there is unsupported options
        // in the block json and either it or this script need to be updated.
        // In testing I wasn't able to get the code to error so...
        echo "<h1>Error generating data object</h1>";
        echo "<p>Please check the types defined in the block.json file are valid</p>";
        echo "<pre>";
        print_r($type);
        echo "<pre>";
        exit;
    }, $args);
}

function resolveableArgs($args)
{
    // Remove classes and booleans from default presentations
    return array_filter($args, function ($value, $key) {
        return $key !== 'classes' && $value !== 'bool';
    }, ARRAY_FILTER_USE_BOTH);
}

function resolveStringTypeData($string)
{
    return isComponentType($string) ?
        resolveComponentTypeData($string)
        : getData($string);
}

function resolveComponentTypeData($component)
{
    $json = componentJSON($component);
    return resolveArgs($json->args);
}

function resolveObjectTypeData($object, $render = false)
{
    // Check if we need to resolve a component or just render the arguments
    if (!empty($object->component)) {
        // Resolve the required data
        $renderedData = resolveStringTypeData($object->component);
    } else {
        $myObject = (array) $object;
        unset($myObject['count']);

        $renderedData = resolveArgs($myObject);
    }

    // Determine the amount of components we need to render
    $quantity = 1;

    // An iterator to know how many components we have so we can create more
    $count = 0;

    if (property_exists($object, 'count')) {
        // Count should be an integer or an array
        $quantity = is_int($object->count) ? $object->count : $object->count[0];
    }

    // How many of the rendered datas do we need
    if ($quantity === 1) {
        return $render === true ?
            \Granola\Component::get($object->component, $renderedData)
            : $renderedData;
    }

    $multiple = [];

    // Create a loop that will resolve the amount of block data we need
    while ($count < $quantity) {
        $multiple[] = $renderedData;
        $count++;
    }

    if ($render === true) {
        return array_reduce($multiple, function ($carry, $item) use ($object) {
            return $carry . \Granola\Component::get($object->component, $item);
        }, '');
    }

    return $multiple;
}

function componentJSON($component)
{
    // The reason we dont convert this to json using assetContent is that we
    // need to only convert part of the data to an array, not the whole thing
    $json = \Granola\Asset::content(str_replace('assets/', '', $component) . '/block.json');

    if (empty($json)) {
        return '';
    }

    // Decode the string to a json object
    $json = json_decode($json);

    // Convert the args to an array
    $json->args = (array) $json->args;

    return $json;
}

function generateVariations($json, $data): array
{
    $variations = [];

    foreach ($json->args as $fieldName => $fieldOptions) {
        if (hasVariations($fieldOptions)) {
            $options = $fieldOptions;

            if (!empty($options->count)) {
                // Count will be an array of numbers
                $options = $options->count;
            } elseif ($options === 'bool') {
                $options = [false, true];
            }

            // Loop the argument values that can be provided
            foreach ($options as $optionKey => $optionValue) {
                $render = false;
                // Create a clone of the data so we can make a variation
                $variationArgs = $data;

                if ($fieldName === 'classes') {
                    $render = true;
                    // Classes get special treatment as they need to
                    // be passed as an array and not a raw value
                    $variationArgs[$fieldName] = [$optionValue];
                } elseif ($optionKey !== 0) {
                    // We never want to render the default option as that will be
                    // already handled for us in the base block
                    if ($fieldOptions === 'bool') {
                        $render = true;
                        // We always render the default with false so this
                        // variation should be a value of true
                        $variationArgs[$fieldName] = $optionValue;
                    } elseif (!empty($fieldOptions->count) && is_array($fieldOptions->count)) {
                        // This should be an object that needs to be added
                        // multiple times.
                        $render = true;

                        // Create a template from the already existing data
                        $template = $data[$fieldName][0];

                        // Turn the variation argument in to an array
                        $variationArgs[$fieldName] = [];
                        $i = 0;

                        while ($i < $optionValue) {
                            $variationArgs[$fieldName][] = $template;
                            $i++;
                        }
                    } else {
                        $render = true;
                        $variationArgs[$fieldName] = resolveStringTypeData($optionValue);

                        if (isComponentType($optionValue)) {
                            $variationArgs[$fieldName] = \Granola\Component::get(
                                $optionValue,
                                $variationArgs[$fieldName]
                            );
                        }
                    }
                }

                if ($render === true) {
                    $variationArgs['_variation'] = $json->name . ' [' . $fieldName . ': ' . $optionValue . ']';
                    $variations[] = $variationArgs;
                }
            }
        }
    }

    return $variations;
}

function getData(string $type)
{
    // Pull the data we will be inserting
    $data = json_decode(\Granola\Asset::content('components/style-guide/data.json'), true);

    return $data[$type];
}

function isComponentType($path): bool
{
    return \Granola\Helpers::startsWith($path, 'assets/components');
}

function hasVariations($arg): bool
{
    // If the argument is an array, it means theres variations. Also if it
    // is a boolean or a object we will need to loop to make the variations
    return (!empty($arg->count) && is_array($arg->count)) || is_array($arg) || $arg === 'bool';
}
