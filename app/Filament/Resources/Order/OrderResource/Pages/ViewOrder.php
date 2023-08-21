<?php

namespace App\Filament\Resources\Order\OrderResource\Pages;

use App\Filament\Resources\Order\OrderResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms;
use Suleymanozev\FilamentRadioButtonField\Forms\Components\RadioButton;
use Tests\HtmlSanitizer\Extension\CustomExtension;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected static string | array $middlewares = ['permission:View Confirmed Bookings'];

    protected static ?string $title = 'Order';

      protected static string $view = 'filament.resources.order.pages.view-order';

      public function changeStatus($status)
      {
          $this->record->status = $status;
          $this->record->save();
      }

    protected function getActions(): array
    {
        return [
//            Actions\EditAction::make(),
        ];
    }
}
