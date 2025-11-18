# Logos das Lojas Participantes

Esta pasta contém os logos das lojas que participam do movimento Black Braune.

## Como adicionar um logo

1. **Formato**: PNG ou JPG (PNG preferível para logos com fundo transparente)
2. **Tamanho mínimo**: 300x300 pixels
3. **Qualidade**: Alta resolução para boa visualização
4. **Fundo**: Transparente ou branco
5. **Nome do arquivo**: Use o padrão `nome-da-loja.png` (sem espaços, use hífens)

## Exemplos de nomenclatura
- `loja-da-maria.png`
- `restaurante-tempero-bom.png` 
- `boutique-elegante.png`
- `tech-store-friburgo.png`

## Como usar no código

Para adicionar um logo na página inicial, substitua os cards de exemplo por:

```html
<div class="loja-logo-card" style="...">
    <img src="<?= Router::url('assets/lojas/nome-da-loja.png') ?>" 
         alt="Nome da Loja" 
         style="height: 120px; width: auto; object-fit: contain; margin-bottom: 1rem; border-radius: 10px;">
</div>
```

## Contato

Para enviar logos ou mais informações, entre em contato através do formulário de cadastro na página ou pelos canais oficiais do Black Braune.