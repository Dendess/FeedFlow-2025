<x-app-layout>
    <x-slot name="header">
        <div class="org-header">
            <h2 class="org-header-title">{{ $organization->name }}</h2>
            <div class="org-header-actions">
                @can('update', $organization)
                    <a href="{{ route('organizations.edit', $organization) }}" class="org-btn-edit">
                        ✏️ Modifier infos
                    </a>
                @endcan
                <a href="{{ route('organizations.index') }}" class="org-action-link">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="org-page-section">
        <div class="org-container">

            @if(session('success'))
                <div class="org-alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="org-alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="org-alert-error">
                    <ul class="org-error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="org-grid org-grid--show">

                <section class="org-card org-panel">
                    <div class="org-panel-heading">
                        <div class="org-panel-icon">ℹ️</div>
                        <div>
                            <h3 class="org-card-title">Détails</h3>
                            <p class="org-card-desc">Informations générales de l'organisation.</p>
                        </div>
                    </div>
                    <dl class="org-details-content">
                        <div class="org-detail-row">
                            <dt>Propriétaire</dt>
                            <dd>{{ optional($organization->owner)->first_name ?? 'Inconnu' }} {{ optional($organization->owner)->last_name }}</dd>
                        </div>
                        <div class="org-detail-row">
                            <dt>Créée le</dt>
                            <dd>{{ $organization->created_at->format('d/m/Y') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="org-card org-panel org-panel--team">
                    <div class="org-panel-heading">
                        <div class="org-panel-icon">👥</div>
                        <div>
                            <h3 class="org-card-title">Membres de l'équipe</h3>
                            <p class="org-card-desc">Visualisez et gérez l'accès à l'organisation.</p>
                        </div>
                    </div>

                    @can('update', $organization)
                        <div class="org-add-member-wrapper org-add-member-wrapper--wide">
                            <form action="{{ route('organizations.users.store', $organization) }}" method="POST">
                                @csrf
                                <div class="org-form-grid">
                                    <div>
                                        <label for="email" class="org-label">Adresse email</label>
                                        <input type="email" name="email" id="email" placeholder="email@exemple.com" class="org-input" required>
                                    </div>
                                    <div>
                                        <label for="role" class="org-label">Rôle</label>
                                        <select name="role" id="role" class="org-input">
                                            <option value="member">Membre</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="org-form-actions-inline">
                                        <button type="submit" class="org-btn-primary">Ajouter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endcan

                    <div class="org-members-table">
                        <div class="org-members-table__head">
                            <span>Membre</span>
                            <span>Rôle</span>
                            <span class="org-members-table__head-actions">Actions</span>
                        </div>

                        @foreach($organization->users as $user)
                            <div class="org-member-row">
                                <div class="org-member-ident">
                                    <div class="org-avatar-circle">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="org-user-info">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="org-user-email">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <div class="org-member-role">
                                    @if($organization->user_id === $user->id)
                                        <span class="org-badge org-badge-owner">👑 Propriétaire</span>
                                    @elseif($user->pivot->role === 'admin')
                                        <span class="org-badge org-badge-admin">🛡️ Admin</span>
                                    @else
                                        <span class="org-badge org-badge-member">Membre</span>
                                    @endif
                                </div>

                                <div class="org-member-actions">
                                    @can('update', $organization)
                                        @if($organization->user_id !== $user->id && $user->id !== auth()->id())
                                            <form action="{{ route('organizations.users.destroy', [$organization, $user]) }}" method="POST" class="org-inline-form" onsubmit="return confirm('Voulez-vous vraiment retirer cet utilisateur ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="org-btn-delete">Retirer</button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>