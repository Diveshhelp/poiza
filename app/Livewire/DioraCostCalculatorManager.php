<?php

namespace App\Livewire;

use App\Models\DioraProduct;
use App\Models\DioraProductCostTemplate;
use Livewire\Component;
use Livewire\WithPagination;

class DioraCostCalculatorManager extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedProductId = null;

    // Form / Live Inputs
    public $mortise_set = 320;
    public $pinjaru_dabi = 80;
    public $box = 25;
    public $packaging = 10;
    public $masalo = 25;
    public $profit_margin_percent = 15;

    public $buff_matt = 42;
    public $buff_glossy = 82;
    public $pvd_cost = 96;

    public $black_color = 40;
    public $antique_color = 60;

    public $two_pc_matt_black = 26;
    public $two_pc_matt_buff = 20;
    public $two_pc_matt_bolt = 2;
    public $two_pc_pvd_color = 30;
    public $two_pc_pvd_buff = 26;
    public $two_pc_pvd_bolt = 2;

    public $computedTwoPcPvdTotal;
    public function updatedSelectedProductId($productId)
    {
        if ($productId) {
            $template = DioraProductCostTemplate::where('diora_product_id', $productId)->first();
            if ($template) {
                // Load saved template data for this product so you can reuse or update it
                $this->mortise_set = $template->mortise_set;
                $this->pinjaru_dabi = $template->pinjaru_dabi;
                $this->box = $template->box;
                $this->packaging = $template->packaging;
                $this->masalo = $template->masalo;
                $this->profit_margin_percent = $template->profit_margin_percent;
                $this->buff_matt = $template->buff_matt;
                $this->buff_glossy = $template->buff_glossy;
                $this->pvd_cost = $template->pvd_cost;
                $this->black_color = $template->black_color;
                $this->antique_color = $template->antique_color;
                $this->two_pc_matt_black = $template->two_pc_matt_black;
                $this->two_pc_matt_buff = $template->two_pc_matt_buff;
                $this->two_pc_matt_bolt = $template->two_pc_matt_bolt;
                $this->two_pc_pvd_color = $template->two_pc_pvd_color;
                $this->two_pc_pvd_buff = $template->two_pc_pvd_buff;
                $this->two_pc_pvd_bolt = $template->two_pc_pvd_bolt;
            }
        }
    }

    public function saveOrUpdateTemplate()
    {
        if (!$this->selectedProductId) {
            session()->flash('error', 'Please select a product first to save or update its cost template.');
            return;
        }

        DioraProductCostTemplate::updateOrCreate(
            ['diora_product_id' => $this->selectedProductId],
            [
                'mortise_set'           => $this->mortise_set,
                'pinjaru_dabi'          => $this->pinjaru_dabi,
                'box'                   => $this->box,
                'packaging'             => $this->packaging,
                'masalo'                => $this->masalo,
                'profit_margin_percent' => $this->profit_margin_percent,
                'buff_matt'             => $this->buff_matt,
                'buff_glossy'           => $this->buff_glossy,
                'pvd_cost'              => $this->pvd_cost,
                'black_color'           => $this->black_color,
                'antique_color'         => $this->antique_color,
                'two_pc_matt_black'     => $this->two_pc_matt_black,
                'two_pc_matt_buff'      => $this->two_pc_matt_buff,
                'two_pc_matt_bolt'      => $this->two_pc_matt_bolt,
                'two_pc_pvd_color'      => $this->two_pc_pvd_color,
                'two_pc_pvd_buff'       => $this->two_pc_pvd_buff,
                'two_pc_pvd_bolt'       => $this->two_pc_pvd_bolt,
            ]
        );

        session()->flash('message', 'Cost template saved/updated successfully for this product!');
    }

    // Getters for real-time calculations
    public function getBaseCommonProperty()
    {
        return (float)$this->mortise_set + (float)$this->pinjaru_dabi + (float)$this->box + (float)$this->packaging + (float)$this->masalo;
    }

    public function getFinalMattProperty()
    {
        $subtotal = $this->baseCommon + (float)$this->buff_matt;
        return round($subtotal + ($subtotal * ((float)$this->profit_margin_percent / 100)), 2);
    }

    public function getFinalBlackProperty()
    {
        return round($this->finalMatt + (float)$this->black_color, 2);
    }

    public function getFinalAntiqueProperty()
    {
        return round($this->finalMatt + (float)$this->antique_color, 2);
    }

    public function getFinalPvdProperty()
    {
        $subtotal = $this->baseCommon + (float)$this->buff_glossy + (float)$this->pvd_cost;
        return round($subtotal + ($subtotal * ((float)$this->profit_margin_percent / 100)), 2);
    }

    public function getTwoPcMattTotalProperty()
    {
        return (float)$this->two_pc_matt_black + (float)$this->two_pc_matt_buff + (float)$this->two_pc_matt_bolt;
    }

    public function getTwoPcPvdTotalProperty()
    {
        return (float)$this->two_pc_pvd_color + (float)$this->two_pc_pvd_buff + (float)$this->two_pc_pvd_bolt;
    }

    public function render()
    {
        $products = DioraProduct::with('costTemplate')
            ->when($this->search, function($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_code', 'like', '%' . $this->search . '%')
                  ->orWhere('model_key', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $allProductsForDropdown = DioraProduct::orderBy('product_name')->get();

        return view('livewire.diora-cost-calculator-manager', [
            'products' => $products,
            'allProductsForDropdown' => $allProductsForDropdown,
            'computedMatt' => $this->finalMatt,
            'computedBlack' => $this->finalBlack,
            'computedAntique' => $this->finalAntique,
            'computedPvd' => $this->finalPvd,
            'computedTwoPcMatt' => $this->twoPcMattTotal,
            'computedTwoPcPvd' => $this->twoPcPvdTotal,
        ])->layout('layouts.app');
    }
}