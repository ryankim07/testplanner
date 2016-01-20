<?php namespace App;

/**
 * Class Grid
 *
 * Model
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

class Grid
{

    /**
     * Prepare columns for header
     *
     * @param $order
     * @param $columnsToDisplay
     * @return array
     */
    public static function prepareColumns($order, $columnsToDisplay)
    {
        $columns['first_name'] = [
            'type'       => 'text',
            'colname'    => 'First',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'first_name',
            'order'      => $order,
            'filterable' => true,
            'width'      => '40px'
        ];

        $columns['last_name'] = [
            'type'       => 'text',
            'colname'    => 'Last',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'last_name',
            'order'      => $order,
            'filterable' => true,
            'width'      => '40px'
        ];

        $columns['admin'] = [
            'type'       => 'text',
            'colname'    => 'Admin',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'admin',
            'order'      => $order,
            'filterable' => true,
            'width'      => '80px'
        ];

        $columns['email'] = [
            'type'       => 'text',
            'colname'    => 'Email',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'email',
            'order'      => $order,
            'filterable' => true,
            'width'      => '80px'
        ];

        $columns['active'] = [
            'type'       => 'text',
            'colname'    => 'Active',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'email',
            'width'      => '10px'
        ];

        $columns['role_names'] = [
            'type'       => 'text',
            'colname'    => 'Roles',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'width'      => '100px'
        ];

        $columns['description'] = [
            'type'       => 'text',
            'colname'    => 'Description',
            'data'       => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'   => 'description',
            'order'      => $order,
            'filterable' => true,
            'width'      => '100px'
        ];

        $columns['status'] = [
            'type'         => 'text',
            'colname'      => 'Status',
            'data'         => ['class' => 'form-control input-sm', 'id' => 'search-term'],
            'sortable'     => 'status',
            'order'        => $order,
            'filterable'   => true,
            'width'        => '10px',
            'header_align' => 'text-center'
        ];

        $columns['activity'] = [
            'type'       => 'text',
            'colname'    => 'Activity',
            'filterable' => true,
            'width'      => '400px'
        ];

        $columns['created_at'] = [
            'type'       => 'date',
            'colname'    => 'Created',
            'from_data'  => ['class' => 'form-control input-sm', 'id' => 'created_from'],
            'to_data'    => ['class' => 'form-control input-sm', 'id' => 'created_to'],
            'from_index' => 'created_from',
            'to_index'   => 'created_to',
            'sortable'   => 'created_at',
            'filterable' => true,
            'order'      => $order,
            'width'      => '20px'
        ];

        $columns['updated_at'] = [
            'type'     => 'text',
            'colname'  => 'Updated',
            'width'    => '20px'
        ];

        $columns['testers'] = [
            'type'       => 'text',
            'colname'    => 'Testers',
            'data'       => ['class' => 'form-control'],
            'width'      => '30px'
        ];

        $columns['view'] = [
            'type'         => 'text',
            'colname'      => 'View',
            'data'         => ['class' => 'form-control'],
            'width'        => '10px',
            'header_align' => 'text-center'
        ];

        $columns['edit'] = [
            'type'         => 'text',
            'colname'      => 'Edit',
            'data'         => ['class' => 'form-control'],
            'width'        => '10px',
            'header_align' => 'text-center'
        ];

        $columns['respond'] = [
            'type'         => 'text',
            'colname'      => 'Respond',
            'data'         => ['class' => 'form-control'],
            'width'        => '10px',
            'header_align' => 'text-center'
        ];

        foreach($columnsToDisplay as $column) {
            $results[] = self::addColumn($column, $columns[$column]);
        }

        return $results;
    }

    /**
     * Add columns to table header
     *
     * @param $index
     * @param $inputOptions
     * @return array
     */
    public static function addColumn($index, $inputOptions)
    {
        $type        = $inputOptions['type'];
        $colname     = $inputOptions['colname'];
        $data        = isset($inputOptions['data']) ? $inputOptions['data'] : null;
        $order       = isset($inputOptions['order']) ? $inputOptions['order'] : null;
        $sortable    = isset($inputOptions['sortable']) ? $inputOptions['sortable'] : null;
        $filterable  = isset($inputOptions['filterable']) ? $inputOptions['filterable'] : false;
        $headerAlign = isset($inputOptions['header_align']) ? $inputOptions['header_align'] : 'text-left';

        switch($type) {
            case 'text':
                $column = [
                    'type'    => $type,
                    'colname' => $colname,
                    'filters' => [
                        'attr' => [
                            'index' => $index,
                            'data'  => $data
                        ]
                    ],
                    'filterable'   => $filterable,
                    'sortable'     => $sortable,
                    'order'        => $order,
                    'width'        => $inputOptions['width'],
                    'header_align' => $headerAlign
                ];
                break;

            case 'date':
                $column = [
                    'type'    => $type,
                    'colname' => $colname,
                    'filters' => [
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
                    'filterable'   => $filterable,
                    'sortable'     => $sortable,
                    'order'        => $order,
                    'width'        => $inputOptions['width'],
                    'header_align' => $headerAlign
                ];
                break;
        }

        return $column;
    }
}