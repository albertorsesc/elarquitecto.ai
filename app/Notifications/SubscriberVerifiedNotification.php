<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\BlockKit\Blocks\ContextBlock;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;
use Illuminate\Notifications\Slack\SlackMessage;

class SubscriberVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Subscriber $subscriber,
        public int $totalVerifiedSubscribers
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
            ->text('¡Suscripción verificada! :white_check_mark:')
            ->headerBlock('Suscripción Verificada :white_check_mark:')
            ->contextBlock(function (ContextBlock $block) {
                $block->text('Un suscriptor ha verificado su correo electrónico');
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('Detalles de verificación:');
                $block->field("*Email:*\n{$this->subscriber->email}")->markdown();
                $block->field("*Fecha de verificación:*\n".$this->subscriber->verified_at->format('Y-m-d H:i:s'))->markdown();
            })
            ->sectionBlock(function (SectionBlock $block) {
                $block->field("*Total de suscriptores verificados:*\n{$this->totalVerifiedSubscribers}")->markdown();
            })
            ->dividerBlock()
            ->sectionBlock(function (SectionBlock $block) {
                $block->text('El Arquitecto AI - Sistema de suscripciones');
            });
    }
}
