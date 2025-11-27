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

            <div class="org-grid">
                
                <div class="org-card">
                    <h3 class="org-card-title">Détails</h3>
                    <p class="org-card-desc">Informations générales.</p>
                    
                    <div class="org-details-content">
                        <p><strong>Propriétaire :</strong> {{ optional($organization->owner)->first_name ?? 'Inconnu' }} {{ optional($organization->owner)->last_name }}</p>
                        <p><strong>Créée le :</strong> {{ $organization->created_at->format('d/m/Y') }}</p>
                        @if($organization->description)
                            <p style="margin-top:0.5rem; color: #4b5563;">{{ $organization->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="org-card org-col-span-2">
                    <h3 class="org-card-title">Membres de l'équipe</h3>
                    <p class="org-card-desc">Gérez les accès à cette organisation.</p>

                    @can('update', $organization)
                        <div class="org-add-member-wrapper">
                            <form action="{{ route('organizations.users.store', $organization) }}" method="POST">
                                @csrf
                                <div class="org-form-row">
                                    <div class="org-form-group-grow">
                                        <label for="email" class="org-label">Ajouter un utilisateur</label>
                                        <input type="email" name="email" id="email" placeholder="email@exemple.com" class="org-input" required>
                                    </div>
                                    <div class="org-form-group-fixed">
                                        <label for="role" class="org-label">Rôle</label>
                                        <select name="role" id="role" class="org-input">
                                            <option value="member">Membre</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="org-btn-primary">Ajouter</button>
                                </div>
                            </form>
                        </div>
                    @endcan

                    <div class="org-users-list">
                        @foreach($organization->users as $user)
                            <div class="org-card-footer">
                                <div class="org-card-left">
                                    <div class="org-avatar-circle">
                                        {{ substr($user->first_name, 0, 1) }}
                                    </div>
                                    
                                    <div>
                                        <div class="org-user-info">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        <div class="org-user-email">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <div class="org-header-actions">
                                    
                                    {{-- LOGIQUE DES BADGES --}}
                                    @if($organization->user_id === $user->id)
                                        <span class="org-badge org-badge-owner">
                                            👑 Propriétaire
                                        </span>
                                    @elseif($user->pivot->role === 'admin')
                                        <span class="org-badge org-badge-admin">
                                            🛡️ Admin
                                        </span>
                                    @else
                                        <span class="org-badge org-badge-member">
                                            Membre
                                        </span>
                                    @endif

                                    {{-- LOGIQUE DU BOUTON SUPPRIMER --}}
                                    @can('update', $organization)
                                        {{-- On ne peut pas supprimer le propriétaire ni soi-même --}}
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>