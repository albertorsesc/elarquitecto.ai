<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class NewSubscriberNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Subscriber $subscriber,
        public int $totalSubscribers
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     */
    public function toSlack(object $notifiable): SlackMessage
    {
        return (new SlackMessage)
            ->text('¡Nuevo suscriptor!')
            ->headerBlock('Nuevo Suscriptor')
            ->contextBlock(function (ContextBlock $block) {
                $block->text('Se ha registrado un nuevo suscriptor');
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('Detalles del suscriptor:');
                $block->field("*Email:*\n{$this->subscriber->email}")->markdown();
                $block->field("*Fecha:*\n".now()->format('Y-m-d H:i:s'))->markdown();
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*Total de suscriptores:*\n{$this->totalSubscribers}")->markdown();
            })
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('El Arquitecto AI - Sistema de suscripciones');
            });
    }
}
