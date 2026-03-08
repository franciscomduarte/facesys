<x-landing-layout>
    <x-slot:title>Política de Privacidade - SkinFlow</x-slot:title>

    <section class="pt-32 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-2">Política de Privacidade</h1>
            <p class="text-gray-500 mb-12">Última atualização: {{ date('d/m/Y') }}</p>

            <div class="prose prose-gray max-w-none space-y-8">
                <div>
                    <h2 class="text-2xl font-bold mb-4">1. Introdução</h2>
                    <p class="text-gray-600 leading-relaxed">A SkinFlow ("nós", "nosso") está comprometida com a proteção da privacidade e dos dados pessoais de seus usuários e dos pacientes cujas informações são armazenadas em nossa plataforma. Esta Política de Privacidade descreve como coletamos, utilizamos, armazenamos, compartilhamos e protegemos dados pessoais, em conformidade com a Lei Geral de Proteção de Dados Pessoais (Lei nº 13.709/2018 — LGPD).</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">2. Dados que Coletamos</h2>

                    <h3 class="text-lg font-semibold mt-6 mb-3">2.1. Dados dos Profissionais (Usuários)</h3>
                    <ul class="list-disc pl-6 space-y-2 text-gray-600">
                        <li>Nome completo e dados de identificação profissional</li>
                        <li>E-mail e telefone de contato</li>
                        <li>Dados de acesso (e-mail e senha criptografada)</li>
                        <li>Informações da clínica (razão social, CNPJ, endereço)</li>
                        <li>Dados de pagamento e cobrança</li>
                        <li>Registros de acesso e logs de utilização do Sistema</li>
                    </ul>

                    <h3 class="text-lg font-semibold mt-6 mb-3">2.2. Dados dos Pacientes</h3>
                    <p class="text-gray-600 leading-relaxed">Os dados dos pacientes são inseridos pelos profissionais usuários do Sistema e podem incluir:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li>Nome completo, CPF, data de nascimento e dados de contato</li>
                        <li>Histórico clínico e informações de saúde</li>
                        <li>Registros de procedimentos estéticos realizados</li>
                        <li>Fotografias clínicas (antes/depois)</li>
                        <li>Prescrições e documentos assinados eletronicamente</li>
                        <li>Dados do mapa facial (pontos de aplicação)</li>
                    </ul>
                    <p class="text-gray-600 leading-relaxed mt-3"><strong>Importante:</strong> Os dados de pacientes são considerados dados pessoais sensíveis (dados de saúde) nos termos da LGPD. O profissional usuário do Sistema é o controlador desses dados e deve garantir que possui base legal adequada (como consentimento do paciente) para o tratamento.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">3. Como Utilizamos os Dados</h2>
                    <p class="text-gray-600 leading-relaxed">Utilizamos os dados coletados para:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li>Fornecer e manter o funcionamento do Sistema</li>
                        <li>Gerenciar contas de usuários e autenticação</li>
                        <li>Processar pagamentos e gerenciar assinaturas</li>
                        <li>Enviar comunicações sobre o serviço (atualizações, manutenções)</li>
                        <li>Melhorar a experiência do usuário e desenvolver novas funcionalidades</li>
                        <li>Cumprir obrigações legais e regulatórias</li>
                        <li>Garantir a segurança da plataforma</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">4. Base Legal para o Tratamento</h2>
                    <p class="text-gray-600 leading-relaxed">O tratamento de dados pessoais pela SkinFlow fundamenta-se nas seguintes bases legais da LGPD:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li><strong>Execução de contrato:</strong> para fornecer os serviços contratados (Art. 7º, V)</li>
                        <li><strong>Consentimento:</strong> quando necessário para finalidades específicas (Art. 7º, I)</li>
                        <li><strong>Obrigação legal:</strong> para cumprimento de obrigações legais e regulatórias (Art. 7º, II)</li>
                        <li><strong>Legítimo interesse:</strong> para melhorias no serviço e segurança da plataforma (Art. 7º, IX)</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">5. Compartilhamento de Dados</h2>
                    <p class="text-gray-600 leading-relaxed">Não vendemos, alugamos ou compartilhamos dados pessoais com terceiros para fins comerciais. Os dados poderão ser compartilhados apenas nas seguintes situações:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li><strong>Prestadores de serviço:</strong> provedores de hospedagem, processadores de pagamento e serviços essenciais para operação do Sistema, sempre mediante contratos que garantam a proteção dos dados</li>
                        <li><strong>Obrigação legal:</strong> quando exigido por lei, regulamentação ou ordem judicial</li>
                        <li><strong>Proteção de direitos:</strong> para proteger nossos direitos, propriedade ou segurança</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">6. Armazenamento e Segurança</h2>
                    <p class="text-gray-600 leading-relaxed">Implementamos medidas técnicas e organizacionais para proteger os dados pessoais, incluindo:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li>Criptografia de dados em trânsito (SSL/TLS) e em repouso</li>
                        <li>Controle de acesso baseado em funções e permissões</li>
                        <li>Isolamento de dados entre diferentes clínicas (multi-tenant)</li>
                        <li>Backups automáticos e regulares</li>
                        <li>Monitoramento de acessos e logs de auditoria</li>
                        <li>Senhas armazenadas com hash criptográfico (nunca em texto puro)</li>
                    </ul>
                    <p class="text-gray-600 leading-relaxed mt-3">Os dados são armazenados em servidores seguros localizados no Brasil ou em países que ofereçam nível adequado de proteção de dados.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">7. Retenção de Dados</h2>
                    <p class="text-gray-600 leading-relaxed">Os dados pessoais são mantidos pelo período necessário para cumprir as finalidades para as quais foram coletados:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li><strong>Dados de conta:</strong> durante a vigência da assinatura e por 90 dias após o cancelamento</li>
                        <li><strong>Dados de pacientes:</strong> durante a vigência da assinatura. Após cancelamento, o profissional deve exportar os dados dentro do prazo de 90 dias</li>
                        <li><strong>Dados de pagamento:</strong> pelo período exigido pela legislação tributária (5 anos)</li>
                        <li><strong>Logs de acesso:</strong> por 6 meses, conforme Marco Civil da Internet</li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">8. Direitos do Titular dos Dados</h2>
                    <p class="text-gray-600 leading-relaxed">Em conformidade com a LGPD, você tem direito a:</p>
                    <ul class="list-disc pl-6 mt-3 space-y-2 text-gray-600">
                        <li>Confirmar a existência de tratamento de seus dados pessoais</li>
                        <li>Acessar seus dados pessoais</li>
                        <li>Corrigir dados incompletos, inexatos ou desatualizados</li>
                        <li>Solicitar a anonimização, bloqueio ou eliminação de dados desnecessários</li>
                        <li>Solicitar a portabilidade dos dados</li>
                        <li>Revogar o consentimento, quando aplicável</li>
                        <li>Obter informações sobre compartilhamento de dados</li>
                    </ul>
                    <p class="text-gray-600 leading-relaxed mt-3"><strong>Para pacientes:</strong> Solicitações relativas a dados de pacientes devem ser direcionadas ao profissional/clínica responsável pelo tratamento. A SkinFlow atuará como operadora dos dados, auxiliando o profissional no atendimento das solicitações.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">9. Cookies e Tecnologias de Rastreamento</h2>
                    <p class="text-gray-600 leading-relaxed">Utilizamos cookies essenciais para o funcionamento do Sistema, como cookies de sessão e autenticação. Não utilizamos cookies de rastreamento para publicidade de terceiros.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">10. Incidentes de Segurança</h2>
                    <p class="text-gray-600 leading-relaxed">Em caso de incidente de segurança que possa acarretar risco ou dano relevante aos titulares dos dados, comunicaremos a Autoridade Nacional de Proteção de Dados (ANPD) e os titulares afetados, conforme previsto na LGPD, descrevendo a natureza dos dados afetados, as medidas adotadas e as orientações aos titulares.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">11. Alterações nesta Política</h2>
                    <p class="text-gray-600 leading-relaxed">Esta Política de Privacidade pode ser atualizada periodicamente. Alterações significativas serão comunicadas por e-mail ou notificação no Sistema com pelo menos 30 (trinta) dias de antecedência. Recomendamos a revisão periódica desta página.</p>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">12. Encarregado de Dados (DPO)</h2>
                    <p class="text-gray-600 leading-relaxed">Para exercer seus direitos ou para dúvidas sobre esta Política de Privacidade, entre em contato com nosso Encarregado de Proteção de Dados:</p>
                    <ul class="list-none mt-3 space-y-1 text-gray-600">
                        <li>WhatsApp: (61) 99865-2709</li>
                        <li>Página de contato: <a href="{{ route('landing.contato') }}" class="text-rose-500 hover:text-rose-600 underline">skinflow.com/contato</a></li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-4">13. Foro</h2>
                    <p class="text-gray-600 leading-relaxed">Esta Política é regida pelas leis brasileiras. Fica eleito o foro da comarca de Brasília/DF para dirimir quaisquer controvérsias.</p>
                </div>
            </div>
        </div>
    </section>
</x-landing-layout>
