<!-- Header -->
<header class="header">
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <img src="<?= Router::url('assets/logo.png') ?>" style="max-width: 160px; width: 100%;" alt="">
            </div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link">Início</a></li>
                <li><a href="#about" class="nav-link">Sobre</a></li>
                <li><a href="#programacao" class="nav-link">Programação</a></li>
                <li><a href="#lojas" class="nav-link">Lojas</a></li>
                <li><a href="#patrocinadores" class="nav-link">Patrocinadores</a></li>
                <li><a href="#cadastro" class="nav-link">Cadastro</a></li>
            </ul>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>
</header>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-logo">
                <img src="<?= Router::url('assets/logo.png') ?>" alt="Logo Black Braune" class="hero-logo-img">                    
            </div>
            
            <div class="event-dates">
                <h2>27, 28 e 29 de Novembro</h2>
                
            </div>
            <div style="display: flex; flex-direction: column; align-items: center;">
                <img src="<?= Router::url('assets/logo_centro_vivo_acianf.png') ?>" style="width: 300px; margin-bottom: 20px;" alt="">
                <a href="#about" class="scroll-down-btn">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about">
    <div class="container" style="padding-top: 60px;">
        <div class="section-header">
            <h2 class="section-title">

            </h2>
            <p class="section-subtitle">Uma iniciativa para revitalizar o coração comercial de Nova Friburgo</p>
        </div>
        
        <div class="about-intro">
            <p class="about-intro-text">
                <strong>A Avenida Alberto Braune é, desde os anos 60, o principal centro comercial de Nova Friburgo.</strong>
                Hoje, apesar de continuar sendo a principal via do centro da cidade, ela perdeu parte do seu protagonismo. O fluxo de turistas é direcionado para outras localidades e até mesmo os moradores deixam de consumir em suas lojas e restaurantes.
            </p>
        </div>

        <div class="about-content">
            <div class="about-text">
                <h3>Objetivos do Black Braune</h3>
                <ul class="objectives-list">
                    <li><i class="fas fa-check-circle"></i> Revitalizar a Avenida Alberto Braune</li>
                    <li><i class="fas fa-check-circle"></i> Fortalecer o comércio local</li>
                    <li><i class="fas fa-check-circle"></i> Valorizar empreendedores friburguenses</li>
                    <li><i class="fas fa-check-circle"></i> Promover cultura e entretenimento</li>
                    <li><i class="fas fa-check-circle"></i> Conectar empresários e consumidores</li>
                </ul>
            </div>
            <div class="about-image">
                <img src="<?= Router::url('assets/av_alberto_braune.png') ?>" alt="Avenida Alberto Braune" class="historical-image">
                <!-- <p class="image-caption">Avenida Alberto Braune nos anos 1940</p> -->
            </div>
        </div>

        <div class="about-partners">
            <h3>Parceiros</h3>
            <div class="partners-grid">
                <div class="partner-card">
                    <img src="<?= Router::url('assets/logo_acianf.png') ?>" alt="ACIANF - Associação Comercial, Industrial e Agrícola de Nova Friburgo" class="partner-logo">
                </div>
                <div class="partner-card">
                    <img src="<?= Router::url('assets/logo_pmnf.png') ?>" alt="Prefeitura Municipal de Nova Friburgo" class="partner-logo">
                </div>
                <div class="partner-card">
                    <img src="<?= Router::url('assets/logo_sebrae.png') ?>" alt="Sebrae - Apoio Institucional" class="partner-logo">
                </div>                    
            </div>
        </div>
    </div>
</section>

<!-- Programação Section -->
<section id="programacao" class="programacao">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Programação Completa</h2>
            <p class="section-subtitle">Três dias de cultura, música e entretenimento</p>
        </div>
        <div class="program-tabs">
            <?php if (!empty($programacao)): ?>
                <?php 
                $dates = array_keys($programacao);
                $diasSemana = [
                    'Sunday' => 'Dom',
                    'Monday' => 'Seg', 
                    'Tuesday' => 'Ter',
                    'Wednesday' => 'Qua',
                    'Thursday' => 'Qui',
                    'Friday' => 'Sex',
                    'Saturday' => 'Sáb'
                ];
                ?>
                <?php foreach ($dates as $index => $date): ?>
                    <button class="tab-button <?= $index === 0 ? 'active' : '' ?>" data-day="dia<?= $index ?>">
                        <?= $diasSemana[date('l', strtotime($date))] ?> (<?= date('d/m', strtotime($date)) ?>)
                    </button>
                <?php endforeach; ?>
            <?php else: ?>
                <button class="tab-button active" data-day="dia0">Em breve</button>
            <?php endif; ?>
        </div>
        
        <div class="program-content">
            <?php if (!empty($programacao)): ?>
                <?php 
                $dates = array_keys($programacao);
                $diasSemana = [
                    'Sunday' => 'Domingo',
                    'Monday' => 'Segunda-feira', 
                    'Tuesday' => 'Terça-feira',
                    'Wednesday' => 'Quarta-feira',
                    'Thursday' => 'Quinta-feira',
                    'Friday' => 'Sexta-feira',
                    'Saturday' => 'Sábado'
                ];
                $mesesPortugues = [
                    'January' => 'Janeiro',
                    'February' => 'Fevereiro',
                    'March' => 'Março',
                    'April' => 'Abril',
                    'May' => 'Maio',
                    'June' => 'Junho',
                    'July' => 'Julho',
                    'August' => 'Agosto',
                    'September' => 'Setembro',
                    'October' => 'Outubro',
                    'November' => 'Novembro',
                    'December' => 'Dezembro'
                ];
                ?>
                <?php foreach ($dates as $index => $date): ?>
                    <div id="dia<?= $index ?>" class="program-day <?= $index === 0 ? 'active' : '' ?>">
                        <h3><?= $diasSemana[date('l', strtotime($date))] ?> - <?= date('d', strtotime($date)) ?> de <?= $mesesPortugues[date('F', strtotime($date))] ?></h3>
                        <div class="program-info">
                            <?php 
                            $atividades = $programacao[$date];
                            if (!empty($atividades)) {
                                $primeiroHorario = date('H\h', strtotime($atividades[0]['horario_inicio']));
                                $ultimoItem = end($atividades);
                                reset($atividades); // Reset array pointer after end()
                                $ultimoHorario = date('H\h', strtotime($ultimoItem['horario_fim'] ?: $ultimoItem['horario_inicio']));
                                echo "<p class=\"program-schedule\"><strong>{$primeiroHorario} às {$ultimoHorario}</strong></p>";
                            }
                            ?>
                            <p class="program-infrastructure">Com banheiros químicos, seguranças e palco</p>
                        </div>
                        <div class="program-grid">
                            <?php foreach ($atividades as $atividade): ?>
                                <div class="program-item">
                                    <div class="program-time">
                                        <?= date('H:i', strtotime($atividade['horario_inicio'])) ?>
                                        <?php if (!empty($atividade['horario_fim'])): ?>
                                            - <?= date('H:i', strtotime($atividade['horario_fim'])) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="program-details">
                                        <h4><?= htmlspecialchars($atividade['titulo']) ?></h4>
                                        <?php if (!empty($atividade['descricao'])): ?>
                                            <p><?= htmlspecialchars($atividade['descricao']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($atividade['local'])): ?>
                                            <small><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($atividade['local']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="dia0" class="program-day active">
                    <div class="program-info">
                        <p style="text-align: center; color: #666; font-style: italic; padding: 3rem;">
                            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 1rem; display: block; color: var(--secondary-color);"></i>
                            <strong>Programação em desenvolvimento</strong><br>
                            Em breve mais informações serão divulgadas!
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- Lojas Section -->
<section id="lojas" class="lojas">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Lojas Participantes</h2>
            <p class="section-subtitle">Conheça os estabelecimentos que fazem parte do movimento</p>
        </div>
        
        <div class="lojas-logos-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; margin: 3rem 0;">
            <?php if (!empty($lojas) && count($lojas) > 0): ?>
                <?php foreach ($lojas as $loja): ?>
                    <div class="loja-logo-card" style="background: var(--dark-card); padding: 2rem; border-radius: var(--border-radius); border: 1px solid var(--dark-lighter); text-align: center; transition: transform 0.3s ease, border-color 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='var(--secondary-color)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--dark-lighter)'" <?php if (!empty($loja['website'])): ?>onclick="window.open('<?= htmlspecialchars($loja['website']) ?>', '_blank')"<?php endif; ?>>
                        <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; border-radius: var(--border-radius); overflow: hidden;">
                            <?php if (!empty($loja['logo'])): ?>
                                <img src="<?= Router::url('assets/img/uploads/' . $loja['logo']) ?>" 
                                     alt="<?= htmlspecialchars($loja['nome']) ?>" 
                                     style="max-width: 100%; max-height: 100px; width: auto; height: auto; object-fit: contain;">
                            <?php else: ?>
                                <i class="fas fa-store" style="color: var(--gray); font-size: 3rem;"></i>
                            <?php endif; ?>
                        </div>
                        <p style="margin: 0; font-weight: 500; color: var(--text-color);"><?= htmlspecialchars($loja['nome']) ?></p>
                        <?php if (!empty($loja['website'])): ?>
                            <small style="color: var(--secondary-color); display: block; margin-top: 0.5rem;">
                                <i class="fas fa-external-link-alt"></i>
                                Clique para visitar
                            </small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Placeholders quando não há lojas cadastradas -->
                <div class="loja-logo-card" style="background: var(--dark-card); padding: 2rem; border-radius: var(--border-radius); border: 1px solid var(--dark-lighter); text-align: center; transition: transform 0.3s ease, border-color 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='var(--secondary-color)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--dark-lighter)'">
                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; background: var(--dark-lighter); border-radius: var(--border-radius); color: var(--gray); font-size: 3rem;">
                        <i class="fas fa-store"></i>
                    </div>
                    <p style="margin: 0; font-weight: 500; color: var(--text-color);">Em breve</p>
                    <small style="color: var(--gray);">Lojas participantes</small>
                </div>
                
                <div class="loja-logo-card" style="background: var(--dark-card); padding: 2rem; border-radius: var(--border-radius); border: 1px solid var(--dark-lighter); text-align: center; transition: transform 0.3s ease, border-color 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='var(--secondary-color)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--dark-lighter)'">
                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; background: var(--dark-lighter); border-radius: var(--border-radius); color: var(--gray); font-size: 3rem;">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <p style="margin: 0; font-weight: 500; color: var(--text-color);">Em breve</p>
                    <small style="color: var(--gray);">Mais estabelecimentos</small>
                </div>
                
                <div class="loja-logo-card" style="background: var(--dark-card); padding: 2rem; border-radius: var(--border-radius); border: 1px solid var(--dark-lighter); text-align: center; transition: transform 0.3s ease, border-color 0.3s ease;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='var(--secondary-color)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='var(--dark-lighter)'">
                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; background: var(--dark-lighter); border-radius: var(--border-radius); color: var(--gray); font-size: 3rem;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <p style="margin: 0; font-weight: 500; color: var(--text-color);">Seja um parceiro</p>
                    <small style="color: var(--gray);">Junte-se ao movimento</small>
                </div>
            <?php endif; ?>
        </div>
            
            
        </div>
      
        
        <div class="lojas-cta" style="text-align: center; margin-top: 3rem;">
            <p style="font-size: 1.1rem; margin-bottom: 1.5rem; color: var(--text-color);">Sua loja ainda não está participando?</p>
            <a href="#cadastro" class="btn btn-secondary" style="background-color: var(--secondary-color); color: var(--primary-color); padding: 1rem 2rem; border-radius: 50px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-block;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-store" style="margin-right: 0.5rem;"></i>
                Cadastre sua Loja
            </a>
        </div>
    </div>
</section>

<!-- Cadastro Section -->
<section id="cadastro" class="cadastro">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Cadastre-se</h2>
            <p class="section-subtitle">Receba novidades, promoções e informações exclusivas</p>
        </div>
        <div class="cadastro-content">
            <div class="cadastro-info">
                <h3>Por que se cadastrar?</h3>
                <ul class="benefits-list">
                    <li><i class="fas fa-envelope"></i> Receba novidades do movimento</li>
                    <li><i class="fas fa-percent"></i> Promoções exclusivas das lojas</li>
                    <li><i class="fas fa-calendar"></i> Programação atualizada dos eventos</li>
                    <li><i class="fas fa-gift"></i> Ofertas especiais dos parceiros</li>
                    <li><i class="fas fa-bell"></i> Notificações sobre novas ações</li>
                </ul>
            </div>
            <form class="cadastro-form" id="cadastroForm" method="POST" action="/newsletter/store">
                <div id="cadastroMessage" class="alert" style="display: none;"></div>
                
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone">
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" value="Nova Friburgo">
                </div>
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="newsletter" name="newsletter" checked>
                        <span class="checkmark"></span>
                        Quero receber newsletter com novidades e promoções
                    </label>
                </div>
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="termos" name="termos" required>
                        <span class="checkmark"></span>
                        Aceito os termos de uso e política de privacidade *
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">Cadastrar-se</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Enviando...
                    </span>
                </button>
                <input type="hidden" name="ajax" value="1">
            </form>
        </div>
    </div>
</section>

<!-- Patrocinadores Oficiais Section -->
<section id="patrocinadores" class="patrocinadores">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Patrocinadores Oficiais</h2>
            <p class="section-subtitle">Empresas que apoiam e investem no movimento Black Braune</p>
        </div>
        <div class="patrocinadores-grid">
            <div class="patrocinador-logo">
                <img src="<?= Router::url('assets/patrocinadores/FRIONLINE COLORIDA.png') ?>" alt="Frionline - Patrocinador Oficial">
            </div>

            <div class="patrocinador-logo">
                <img src="<?= Router::url('assets/patrocinadores/GRUPO SAF COLORIDA.png') ?>" alt="Grupo SAF - Patrocinador Oficial">
            </div>

            <div class="patrocinador-logo">
                <img src="<?= Router::url('assets/patrocinadores/SICREDI COLORIDA.png') ?>" alt="Sicredi - Patrocinador Oficial">
            </div>

            <div class="patrocinador-logo">
                <img src="<?= Router::url('assets/patrocinadores/UNIMED SERRANA.png') ?>" alt="Unimed Serrana - Patrocinador Oficial">
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Black Braune</h3>
                <p>Movimento de revitalização da Avenida Alberto Braune, fortalecendo o comércio local de Nova Friburgo.</p>
            </div>
            <div class="footer-section">
                <h4>Contato</h4>
                <?php foreach ($contatos as $contato): ?>
                    <p>
                        <?php if ($contato['icone']): ?>
                            <i class="<?= htmlspecialchars($contato['icone']) ?>"></i> 
                        <?php endif; ?>
                        <?php if ($contato['link']): ?>
                            <a href="<?= htmlspecialchars($contato['link']) ?>" class="footer-contact-link">
                                <?= $contato['valor'] ?>
                            </a>
                        <?php else: ?>
                            <?= $contato['valor'] ?>
                        <?php endif; ?>
                    </p>
                <?php endforeach; ?>
            </div>
            <div class="footer-section">
                <h4>Parceiros</h4>
                <ul>
                    <li>ACIANF</li>
                    <li>Prefeitura de Nova Friburgo</li>
                    <li>Sebrae</li>
                    <li>Núcleo Setorial Centro Vivo</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Redes Sociais</h4>
                <div class="social-links">
                    <?php foreach ($redesSociais as $rede): ?>
                        <a href="<?= htmlspecialchars($rede['link']) ?>" target="_blank" title="<?= htmlspecialchars($rede['titulo']) ?>">
                            <i class="<?= htmlspecialchars($rede['icone']) ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p>&copy; 2025 Black Braune. Todos os direitos reservados.</p>
                <div class="developer-credit">
                    <span>Desenvolvido por:</span>
                    <img src="<?= Router::url('assets/logo_com_pmnf.png') ?>" alt="Ponti" class="developer-logo">
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            }
        });
    }

    // Processamento do formulário de cadastro
    const cadastroForm = document.getElementById('cadastroForm');
    const submitBtn = document.getElementById('submitBtn');
    const messageDiv = document.getElementById('cadastroMessage');

    if (cadastroForm) {
        cadastroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostra loading
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
            submitBtn.disabled = true;
            
            // Esconde mensagem anterior
            messageDiv.style.display = 'none';
            
            // Prepara dados do formulário
            const formData = new FormData(cadastroForm);
            
            // Adiciona valores dos checkboxes corretamente
            if (!formData.has('newsletter')) {
                formData.delete('newsletter');
            }
            if (!formData.has('termos')) {
                formData.delete('termos');
            }

            // Envia requisição AJAX
            fetch('/newsletter/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sucesso
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
                    messageDiv.style.display = 'block';
                    
                    // Limpa formulário
                    cadastroForm.reset();
                    document.getElementById('cidade').value = 'Nova Friburgo';
                    document.getElementById('newsletter').checked = true;
                    
                    // Scroll para mensagem
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                } else {
                    // Erro
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.message;
                    messageDiv.style.display = 'block';
                    
                    // Scroll para mensagem
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Erro ao enviar cadastro. Tente novamente.';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                // Restaura botão
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
                submitBtn.disabled = false;
            });
        });
    }
});
</script>