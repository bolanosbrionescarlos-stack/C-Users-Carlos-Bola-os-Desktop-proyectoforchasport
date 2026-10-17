<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Preparing = 'preparing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    /**
     * Etiqueta legible para mostrar en la interfaz.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente de confirmación',
            self::Confirmed => 'Confirmado',
            self::Preparing => 'En preparación',
            self::Shipped => 'Enviado',
            self::Delivered => 'Entregado',
            self::Cancelled => 'Cancelado',
        };
    }

    /**
     * Color asociado al estado, usado como CSS hex.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => '#F59E0B',
            self::Confirmed => '#3B82F6',
            self::Preparing => '#8B5CF6',
            self::Shipped => '#06B6D4',
            self::Delivered => '#10B981',
            self::Cancelled => '#EF4444',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
