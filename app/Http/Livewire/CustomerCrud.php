<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use Livewire\Component;

class CustomerCrud extends Component
{
    public $customers, $name, $phone, $note, $customer_id;
    public $isModalOpen = 0;
    public $isEdit = false;
    public function render()
    {
        $this->customers = Customer::all();
        return view('livewire.customer-crud');
    }
    public function create()
    {
        $this->isEdit = false;
        $this->resetCreateForm();
        $this->openModalPopover();
    }
    public function openModalPopover()
    {
        $this->isModalOpen = true;
    }
    public function closeModalPopover()
    {
        $this->isModalOpen = false;
    }
    private function resetCreateForm(){
        $this->name = '';
        $this->phone = '';
        $this->note = '';
    }
    
    public function store()
    {
        if($this->isEdit){
            $this->validate([
                'name' => 'required',
                'phone' => 'required',
                'note' => 'sometimes',
            ]);
        }else{
            $this->validate([
                'name' => 'required',
                'phone' => 'required',
                'note' => 'sometimes',
            ]);
        }
        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'note' => $this->note,
        ];

        if($this->isEdit){
            Customer::updateOrCreate(['id' => $this->customer_id], $data);
            session()->flash('message', $this->customer_id ? 'Customer updated.' : 'Customer created.');
            $this->closeModalPopover();
            $this->resetCreateForm();
        }else{
            Customer::create($data);
            session()->flash('message', $this->customer_id ? 'Customer updated.' : 'Customer created.');
            $this->closeModalPopover();
            $this->resetCreateForm();
        }
    }

    public function edit($id)
    {
        $this->isEdit = true;
        $customer = Customer::findOrFail($id);
        $this->customer_id = $id;
        $this->name = $customer->name;
        $this->phone = $customer->phone;
        $this->note = $customer->note;
    
        $this->openModalPopover();
    }
    
    public function delete($id)
    {
        Customer::find($id)->delete();
        session()->flash('message', 'Customer deleted.');
    }
}
