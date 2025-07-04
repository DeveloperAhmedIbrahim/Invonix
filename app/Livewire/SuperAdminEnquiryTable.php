<?php

namespace App\Livewire;

use App\Models\SuperAdminEnquiry;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SuperAdminEnquiryTable extends LivewireTableComponent
{
    protected $listeners = ['resetPageTable', 'filterByStatus'];
    protected $model = SuperAdminEnquiry::class;

    protected string $tableName = 'super_admin_enquiries';

    public $selectedStatus = SuperAdminEnquiry::ALL;
    public bool $showButtonOnHeader = true;

    public string $buttonComponent = 'super_admin.enquiries.components.filter';

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('page');
        $this->setDefaultSort('created_at', 'desc');
        $this->setQueryStringStatus(false);

        $this->setThAttributes(function (Column $column) {
            if ($column->getTitle() === 'Message') {
                return [
                    'class' => 'w-50',
                ];
            }
            if ($column->getTitle() === __('messages.common.action')) {
                return [
                    'style' => 'width:9%;text-align:center',
                ];
            }

            return [];
        });

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->getField() === 'id') {
                return [
                    'class' => 'text-center',
                ];
            }

            return [];
        });
    }

    public function columns(): array
    {
        return [
            Column::make(__('messages.enquiry.name'), 'full_name')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.enquiry.message'), 'message')
                ->sortable()
                ->searchable(),
            Column::make(__('messages.enquiry.read').'/'.__('messages.enquiry.unread'), 'status')
                ->sortable()
                ->searchable()
                ->label(function ($row, Column $column) {
                    return  $row->status ? '<div class="badge bg-light-success">Read</div>' : '<div class="badge bg-light-danger">Unread</div>';
                })
                ->html(),
            Column::make(__('messages.common.action'), 'id')
                ->format(function ($value, $row, Column $column) {
                    return view('livewire.action-button')
                        ->withValue([
                            'show-route' => route('super.admin.enquiry.show', $row->id),
                            'data-id' => $row->id,
                            'data-delete-id' => 'enquiry-delete-btn',
                        ]);
                }),
        ];
    }

    public function builder(): Builder
    {
        $query = SuperAdminEnquiry::select('super_admin_enquiries.*')
            ->when($this->getAppliedFilterWithValue('status'), function ($query, $type) {
                return $query->where('status', $type);
            });

        $query->when($this->selectedStatus != SuperAdminEnquiry::ALL, function ($q) {
            $q->where('status', $this->selectedStatus);
        });

        return $query;
    }

    public function filterByStatus($status)
    {
        $this->selectedStatus = $status;
        $this->setBuilder($this->builder());
    }

    public function resetPageTable()
    {
        $this->customResetPage('super_admin_enquiriesPage');
    }

    public function placeholder()
    {
        return view('livewire.enquiry_skeleton');
    }
}
