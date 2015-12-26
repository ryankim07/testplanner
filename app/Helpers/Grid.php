<?php namespace App\Helpers;

/**
 * Class Grid
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    TestPlanner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

class Grid
{
    /**
     * Column attribute
     *
     * @var array
     */
    protected $_columns = array();

    /**
     * Add columns to table header
     *
     * @param $index
     * @param $inputOptions
     * @return array
     */
    public function addColumn($index, $inputOptions)
    {
        $type       = $inputOptions['type'];
        $colname    = $inputOptions['colname'];
        $data       = isset($inputOptions['data']) ? $inputOptions['data'] : null;
        $order      = isset($inputOptions['order']) ? $inputOptions['order'] : null;
        $sortable   = isset($inputOptions['sortable']) ? $inputOptions['sortable'] : null;
        $filterable = isset($inputOptions['filterable']) ? $inputOptions['filterable'] : false;

        switch($type) {
            case 'text':
                $input = [
                    'type'    => $type,
                    'colname' => $colname,
                    'filters' => [
                        'attr'  => [
                            'index' => $index,
                            'data'  => $data
                        ]
                    ],
                    'filterable' => $filterable,
                    'sortable'   => $sortable,
                    'order'      => $order,
                    'width'      => $inputOptions['width']
                ];
                break;

            case 'date':
                $input = [
                    'type'    => $type,
                    'colname' => $colname,
                    'filters'  => [
                        [
                            'attr' => [
                                'label' => 'From:',
                                'index' => $inputOptions['from_index'],
                                'data'  => $inputOptions['from_data']
                            ]
                        ],
                        [
                            'attr' => [
                                'label' => 'To:',
                                'index' => $inputOptions['to_index'],
                                'data'  => $inputOptions['to_data']
                            ]
                        ]
                    ],
                    'filterable' => $filterable,
                    'sortable'   => $sortable,
                    'order'      => $order,
                    'width'      => $inputOptions['width']
                ];
                break;
        }

        array_push($this->_columns, $input);

        return $this->_columns;
    }
}