   <template>
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container-policy">
          <div class="modal-header modal-header-policy">
            <slot name="header">
              {{ trans('text.PersonalDataUsagePolicy') }} 
            </slot>

             <button class="modal-policy-button" @click="$emit('closePopup')">
              {{ trans("text.BackButton") }}
              </button>

          </div>
          <div class="modal-body">
            <slot name="body">             
                <div class="popup-text">    

<div v-if="lang==='ru'">
<ol>
<li><strong> Общие положения</strong></li>
</ol>
<p>Политика обработки персональных данных в ООО «Технологии МЭНСИС» (далее – Политика) определяет основные принципы, цели, права субъектов персональных данных, а также реализуемые в ООО «Технологии МЭНСИС» (далее Компания) меры по защите персональных данных.</p>
<p>Настоящая Политика в отношении обработки персональных данных разработана ООО «Технологии МЭНСИС» в соответствии с требованиями Федерального закона от 27.07.2006 №152-ФЗ «О персональных данных».</p>
<p><strong>&nbsp;</strong></p>
<ol start="2">
<li><strong> Основные термины и определения</strong></li>
</ol>
<p>Персональные данные – любая информация, относящаяся к прямо или косвенно определенному или определяемому физическому лицу (субъекту персональных данных).</p>
<p>Обработка персональных данных – любое действие (операция) или совокупность действий (операций), совершаемые с использованием средств автоматизации или без использования таких средств с персональными данными, включая сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передачу (распространение, предоставление, доступ), обезличивание, блокирование, удаление, уничтожение персональных данных.</p>
<p><strong>&nbsp;</strong></p>
<ol start="3">
<li><strong> Принципы и цели обработки персональных данных</strong></li>
</ol>
<p>Обработка персональных данных в Компании осуществляется на законной и справедливой основе и ограничивается достижением конкретных и законных целей, а именно с целью трудоустройства субъектов персональных данных в компании и на вакантные места, к которым субъекты персональных данных проявили интерес. Обработке подлежат только персональные данные, которые отвечают целям их обработки. Содержание и объем обрабатываемых в Компании персональных данных соответствуют заявленным целям обработки, избыточность обрабатываемых данных не допускается.</p>
<p>Состав и содержание персональных данных, цель обработки персональных данных, категории персональных данных, категории субъектов персональных данных, чьи данные обрабатываются в Компании, перечень действий с персональными данными, допускаемых при их обработке, общее описание используемых оператором способов обработки персональных данных, содержатся во внутренних регламентах Компании.</p>
<p>Персональные данные собираются и обрабатываются Компанией исключительно на законных основаниях, с согласия субъектов персональных данных.</p>
<p><strong>&nbsp;</strong></p>
<ol start="4">
<li><strong> Права субъектов персональных данных</strong></li>
</ol>
<p>Субъекты персональных данных имеют право на:</p>
<p>— полную информацию об их персональных данных, обрабатываемых в ООО «Технологии МЭНСИС»;</p>
<p>— доступ к своим персональным данным, включая право на получение копии любой записи, содержащей их персональные данные, за исключением случаев, предусмотренных федеральным законом;</p>
<p>— уточнение своих персональных данных, их блокирование или уничтожение в случае, если персональные данные являются неполными, устаревшими, неточными, незаконно полученными или не являются необходимыми для заявленной цели обработки;</p>
<p>— отзыв согласия на обработку персональных данных;</p>
<p>— принятие предусмотренных законом мер по защите своих прав;</p>
<p>— обжалование действия или бездействия Компании, осуществляемых с нарушением требований законодательства Российской Федерации в области персональных данных;</p>
<p>— осуществление иных прав, предусмотренных законодательством Российской Федерации.</p>
<p><strong>&nbsp;</strong></p>
<ol start="5">
<li><strong> Меры по защите персональных данных</strong></li>
</ol>
<p>Персональные данные обираемые Компанией относятся к категории простых персональных данных требующих 4-й категории защиты. В целях обеспечения безопасности персональных данных при их обработке Компанией применяет необходимые и достаточные правовые, организационные и технические меры, включающие:</p>
<p>— назначение лица, ответственного за организацию обработки персональных данных в ООО «Технологии МЭНСИС»;</p>
<p>— принятие локальных нормативных актов и иных документов в области обработки и защиты персональных данных;</p>
<p>— получение согласий субъектов персональных данных на обработку их персональных данных, за исключением случаев, предусмотренных законодательством Российской Федерации;</p>
<p>— обособление персональных данных, обрабатываемых без использования средств автоматизации, от иной информации, в частности путем их фиксации на отдельных материальных носителях персональных данных и хранении в специальных помещениях;</p>
<p>— установление запрета на передачу персональных данных по открытым каналам связи, вычислительным сетям вне пределов контролируемой зоны и сетям Интернет без применения установленных в Компании мер по обеспечению безопасности персональных данных (за исключением общедоступных и (или) обезличенных персональных данных);</p>
<p>— хранение материальных носителей персональных данных с соблюдением условий, обеспечивающих сохранность персональных данных и исключающих несанкционированный доступ к ним;</p>
<p>— осуществление внутреннего контроля соответствия обработки персональных данных Федеральному закону «О персональных данных» и принятым в соответствии с ним нормативным правовым актам, требованиям к защите персональных данных, настоящей Политике, локальным нормативным актам Компании.</p>
<p>— иные меры, предусмотренные законодательством Российской Федерации в области персональных данных.</p>
<p>Меры по обеспечению безопасности персональных данных, направлены на защиту персональных данных от неправомерного или случайного доступа к ним, уничтожения, изменения, блокирования, копирования, предоставления, распространения, а также от иных неправомерных действий в отношении персональных данных.</p>
<p><strong>&nbsp;</strong></p>
<ol start="6">
<li><strong> Ответственность</strong></li>
</ol>
<p>Компания несет ответственность за нарушение обязательств по обеспечению безопасности и конфиденциальности персональных данных при их обработке в соответствии с законодательством Российской Федерации.</p>
<p>&nbsp;</p>

</div>

<div v-if="lang==='en'">
<ol>
<li><strong>General provisions</strong></li>
</ol>

<p>The Personal Data Processing Policy at MANSIS Technologies LLC (hereinafter referred to as the Policy) defines the basic principles, goals, rights of personal data subjects, as well as measures implemented at MANSIS Technologies LLC (hereinafter referred to as the Company) to protect personal data.</p>
<p>This Policy regarding the processing of personal data has been developed by MANSIS Technologies LLC in accordance with the requirements of Federal Law No. 152-FZ dated 27.07.2006 "On Personal Data".</p>
<p><strong>&nbsp;</strong></p>

<ol start="2">
<li><strong> Basic terms and definitions</strong></li>
</ol>
<p>Personal data – any information relating directly or indirectly to a specific or identifiable individual (subject of personal data).</p>
<p>Personal data processing – any action (operation) or set of actions (operations) performed with or without the use of automation tools with personal data, including collection, recording, systematization, accumulation, storage, clarification (updating, modification), extraction, use, transfer (distribution, provision, access), depersonalization, blocking, deletion, destruction of personal data.</p>
<p><strong>&nbsp;</strong></p>
<ol start="3">
<li><strong> Principles and purposes of personal data processing</strong></li>
</ol>
<p>The processing of personal data in the Company is carried out on a legal and fair basis and is limited to achieving specific and legitimate goals, namely for the purpose of employment of personal data subjects in the company and for vacant places to which personal data subjects have shown interest. Only personal data that meets the purposes of their processing are subject to processing. The content and volume of personal data processed by the Company correspond to the stated purposes of processing, redundancy of the processed data is not allowed.</p>
<p>The composition and content of personal data, the purpose of personal data processing, categories of personal data, categories of personal data subjects whose data are processed by the Company, a list of actions with personal data allowed during their processing, a general description of the methods used by the operator for processing personal data, are contained in the Company's internal regulations.</p>
<p>Personal data is collected and processed by the Company exclusively on legal grounds, with the consent of the subjects of personal data.</p>
<p><strong>&nbsp;</strong></p>

<ol start="4">
<li><strong> Rights of personal data subjects</strong></li>
</ol>

<p>Personal data subjects have the right to:</p>
<p>— full information about their personal data processed by MANSIS Technologies LLC;</p>
<p>— access to their personal data, including the right to receive a copy of any record containing their personal data, except in cases provided for by federal law;</p>
<p>— clarification of their personal data, their blocking or destruction if the personal data are incomplete, outdated, inaccurate, illegally obtained or are not necessary for the stated purpose of processing;</p>
<p>— withdrawal of consent to the processing of personal data;</p>
<p> — taking legal measures to protect their rights;</p>
<p>— appeal against the actions or omissions of the Company carried out in violation of the requirements of the legislation of the Russian Federation in the field of personal data;</p>
<p> — exercise of other rights provided for by the legislation of the Russian Federation.</p>
<p><strong>&nbsp;</strong></p>
<ol start="5">
<li><strong> Personal data protection measures</strong></li>
</ol>
<p>Personal data collected by the Company belong to the category of simple personal data requiring the 4th category of protection. In order to ensure the security of personal data during their processing, the Company applies the necessary and sufficient legal, organizational and technical measures, including:</p>
<p>— appointment of the person responsible for the organization of personal data processing in MANSIS Technologies LLC;</p>
<p> — adoption of local regulations and other documents in the field of personal data processing and protection;</p>
<p> — obtaining the consent of personal data subjects to the processing of their personal data, except in cases provided for by the legislation of the Russian Federation;</p>
<p>— separation of personal data processed without the use of automation tools from other information, in particular by fixing them on separate material carriers of personal data and storing them in special rooms;</p>
<p>— the establishment of a ban on the transfer of personal data via open communication channels, computer networks outside the controlled zone and the Internet without the use of measures established in the Company to ensure the security of personal data (with the exception of publicly available and (or) depersonalized personal data);</p>
<p> — storage of material carriers of personal data in compliance with the conditions that ensure the safety of personal data and exclude unauthorized access to them;</p>
<p>— implementation of internal control of compliance of personal data processing with the Federal Law "On Personal Data" and regulatory legal acts adopted in accordance with it, requirements for personal data protection, this Policy, local regulations of the Company.</p>
<p> — other measures provided for by the legislation of the Russian Federation in the field of personal data.</p>
<p>Measures to ensure the security of personal data are aimed at protecting personal data from unauthorized or accidental access to them, destruction, modification, blocking, copying, provision, distribution, as well as from other illegal actions with respect to personal data.</p>
<p><strong>&nbsp;</strong></p>
<ol start="6">
<li><strong> Responsibility</strong></li>
</ol>

<p>The Company is responsible for violating the obligations to ensure the security and confidentiality of personal data when processing them in accordance with the legislation of the Russian Federation.</p>
<p>&nbsp;</p>

</div>




                </div>
            </slot>
          </div>
          <div class="modal-footer">
            <slot name="footer">                    
             
            </slot>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import Select2 from 'vue3-select2-component';  
export default {
  props: ['lang'],
  data() {
       return {         
        }
    },
}
</script>

<style>
</style>