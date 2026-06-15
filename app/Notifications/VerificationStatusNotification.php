<?php

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly Verification $verification,
        public readonly string $status,
        public readonly ?string $reason = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $copy = $this->copy();

        $message = (new MailMessage)
            ->subject($copy['subject'])
            ->greeting('Ola, '.$notifiable->name)
            ->line($copy['headline'])
            ->line($copy['body']);

        if ($this->reason) {
            $message->line('Motivo informado: '.$this->reason);
        }

        if ($this->status === Verification::STATUS_APPROVED && $this->verification->verification_code) {
            $message
                ->line('Seu codigo ConfirmaID e '.$this->verification->verification_code.'.')
                ->line('Ele pode ser usado por empresas parceiras para confirmar seu status, sem acesso aos seus documentos.');
        }

        return $message
            ->action('Acessar minha area', route('app.dashboard'))
            ->line('Equipe ConfirmaID');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'verification_id' => $this->verification->id,
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }

    /**
     * @return array{subject: string, headline: string, body: string}
     */
    private function copy(): array
    {
        return match ($this->status) {
            Verification::STATUS_UNDER_REVIEW => [
                'subject' => 'Recebemos sua verificacao',
                'headline' => 'Sua verificacao foi enviada para analise.',
                'body' => 'Vamos revisar seus dados e documentos. Voce recebera uma nova mensagem quando houver uma decisao.',
            ],
            Verification::STATUS_APPROVED => [
                'subject' => 'Sua identidade foi aprovada',
                'headline' => 'Sua identidade foi verificada com sucesso.',
                'body' => 'Seu codigo ConfirmaID ja esta disponivel na sua area.',
            ],
            Verification::STATUS_REJECTED => [
                'subject' => 'Sua verificacao foi reprovada',
                'headline' => 'Nao foi possivel aprovar sua verificacao neste envio.',
                'body' => 'Revise o motivo abaixo e faca um novo envio quando estiver pronto.',
            ],
            Verification::STATUS_CORRECTION_REQUESTED => [
                'subject' => 'Precisamos de uma correcao na sua verificacao',
                'headline' => 'Sua verificacao precisa de ajustes antes da aprovacao.',
                'body' => 'Revise o motivo abaixo e envie novamente as informacoes solicitadas.',
            ],
            Verification::STATUS_BLOCKED => [
                'subject' => 'Sua verificacao foi bloqueada',
                'headline' => 'Sua verificacao foi bloqueada apos analise.',
                'body' => 'No momento, este processo nao pode ser usado para confirmar sua identidade.',
            ],
            default => [
                'subject' => 'Atualizacao da sua verificacao',
                'headline' => 'Ha uma atualizacao na sua verificacao.',
                'body' => 'Acesse sua area para acompanhar o status mais recente.',
            ],
        };
    }
}
